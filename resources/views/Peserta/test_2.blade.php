@extends('peserta.layout.layout_tes')
@section('nav')
<div class="w-full p-2">
    <h3 class="text-center justify-between text-lg font-bold text-gray-900 sm:text-lg dark:text-white mb-2">{{$test->nama}}</h3>
    <div class="w-52 bg-zinc-300 dark:bg-gray-800 rounded-lg shadow p-2">
        <p class="dark:text-white justify-between">Durasi Tes: <span id="countdownTimer" class="dark:text-white font-bold">{{$test->durasi}}</span></p>
    </div>
</div>
@endsection
@section('content')
<div class="relative justify-between shadow-md sm:rounded-lg mt-16">
    <form id="answerForm" method="post" action="{{ route('peserta.submitAnswer', ['plt_kode' => $test->plt_kode, 'test_id' => $test->id]) }}">
        @csrf

        @php
            $shuffledSoalTest = $soal_test->shuffle();
        @endphp

        @foreach ($shuffledSoalTest as $index => $soal)
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <tbody>
                    <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $soal->title }}
                        </th>
                    </tr>
                    <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            @if($soal->file_soal)
                                <div class="w-64 h-64 overflow-hidden">
                                    <img class="w-full h-full object-contain" src="{{ asset('storage/' . $soal->file_soal) }}" alt="{{ $soal->urutan }}">
                                </div>
                            @else
                                <div></div> <!-- Placeholder jika tidak ada gambar -->
                            @endif
                        </th>
                    </tr>
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            @if ($soal->tipe == "Pilihan Ganda")
                                @php
                                    // Get all options for the current question
                                    $options = $jawaban_test->where('soal_id', $soal->id);
                                    $originalOrder = $options->pluck('urutan')->toArray();
                                    $shuffledOrder = collect($originalOrder)->shuffle();
                                    $selectedOption = old('selected_option_' . $soal->urutan) ?? session('selected_option_' . $soal->urutan) ?? null;
                                @endphp

                                <input type="hidden" name="soal_id[{{ $soal->urutan }}]" value="{{ $soal->id }}">
                                <!-- Add these hidden fields inside the form -->
                                <input type="hidden" name="selected_option[{{ $soal->urutan }}]" id="hiddenSelectedOption" value="{{ $selectedOption }}">

                                @foreach ($shuffledOrder as $index => $shuffledIndex)
                                    @php
                                        $jawaban = $options->where('urutan', $shuffledIndex)->first();
                                    @endphp
                                    <label>
                                        <input id="ganda-{{ $soal->urutan }}-{{ $index }}" type="radio" name="selected_option[{{ $soal->urutan }}]" value="{{ $jawaban->id }}" onclick="handleRadioClick({{ $soal->urutan }}, '{{ $jawaban->id }}')" {{ ($jawaban->id == $selectedOption) ? 'checked' : '' }}>
                                        {{ $jawaban->title }}
                                    </label><br>
                                @endforeach

                                <script>
                                    // JavaScript to handle saving and retrieving selected option using localStorage
                                    document.addEventListener('DOMContentLoaded', function () {
                                        var form = document.getElementById('answerForm_{{ $soal->urutan }}');

                                        // Add event listener to the form to clear localStorage when the form is submitted
                                        form.addEventListener('submit', function () {
                                            localStorage.removeItem('ganda-{{ $soal->urutan }}');
                                        });
                                    });

                                    function handleRadioClick(currentQuestionId, selectedOptionId) {
                                        localStorage.setItem('ganda-' + currentQuestionId, selectedOptionId);
                                    }

                                    // Retrieve and set the selected option when the page loads
                                    var storedOption = localStorage.getItem('ganda-{{ $soal->urutan }}');
                                    if (storedOption) {
                                        document.querySelector('input[name="selected_option[{{ $soal->urutan }}]"][value="' + storedOption + '"]').checked = true;
                                    }
                                </script>

                            @elseif ($soal->tipe == "Jawaban Singkat")
                                <input type="hidden" name="soal_id[{{ $soal->urutan }}]" value="{{ $soal->id }}">
                                <input type="text" name="singkat[{{ $soal->urutan }}]" placeholder="Jawaban" id="singkat-{{ $soal->urutan }}"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    value="{{ old('singkat_' . $soal->urutan) ?? session('singkat_' . $soal->urutan) }}">


                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        var jawabanInput = document.getElementById('singkat-{{ $soal->urutan }}');
                                        var form = document.getElementById('answerForm_{{ $soal->urutan }}');
                                        var hiddenJawabanSingkat = document.getElementById('hiddenJawabanSingkat_{{ $soal->urutan }}');

                                        jawabanInput.addEventListener('input', function () {
                                            localStorage.setItem('jawaban-{{ $soal->urutan }}', this.value);
                                            hiddenJawabanSingkat.value = this.value; // Update the hidden field
                                        });

                                        // Your existing short answer input change handling logic
                                        jawabanInput.addEventListener('input', function () {
                                            // Ganti localStorage dengan mengirimkan data ke server menggunakan AJAX
                                            axios.post('/save-answer', {
                                                currentQuestionId: {{ $soal->id }},
                                                jawabanSingkat: this.value
                                            })
                                            .then(response => {
                                                console.log(response.data);
                                            })
                                            .catch(error => {
                                                console.error(error);
                                            });
                                        });

                                        // Retrieve and set the entered text when the page loads
                                        var storedJawaban = localStorage.getItem('jawaban-{{ $soal->urutan }}');
                                        if (storedJawaban) {
                                            jawabanInput.value = storedJawaban;
                                            hiddenJawabanSingkat.value = storedJawaban; // Update the hidden field
                                        }
                                    });
                                </script>
                            @endif
                            
                        </th>
                    </tr>
                </tbody>
            </table>
        @endforeach
        <!-- Tombol "Submit" -->
        <div class="float-right col-span-6 sm:col-full mt-4 mb-4">
            <button data-modal-target="confirmationModal" data-modal-toggle="confirmationModal" class="mr-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Submit</button>
        </div>
        <div id="confirmationModal" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
            <div class="relative p-4 w-full max-w-md max-h-full">
                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                    <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="confirmationModal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                    <div class="p-4 md:p-5 text-center">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        <h3 class="mb-5 text-m font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin mengumpulkan tes ini?</h3>
                        <div class="flex space-x-2 justify-center">
                            
                            <button onclick="submitForm()" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                Ya
                            </button>
                        
                            <button data-modal-hide="confirmationModal" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                Tidak
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <script>
    function submitForm() {
        document.getElementById('answerForm').submit();
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('answerForm');
            form.addEventListener('submit', function () {
                // Membersihkan local storage
                localStorage.clear();
            });
        });
    }
    </script>

    <script>
        // Setelah formulir berhasil disubmit, membersihkan local storage
        document.addEventListener('DOMContentLoaded', function () {
            var form = document.getElementById('answerForm');
            form.addEventListener('submit', function () {
                // Membersihkan local storage
                localStorage.clear();
            });
        });
    </script>
    <!-- JavaScript untuk menghitung mundur durasi tes -->
</div>
<script>
    // Ambil durasi tes dari PHP ke JavaScript
    var duration = '{{$test->durasi}}'; // format: 'HH:MM:SS'

    // Cek apakah ada durasi tersisa di localStorage
    var remainingDuration = localStorage.getItem('remainingDuration');
    if (remainingDuration) {
        duration = remainingDuration;
    }

    // Split durasi menjadi jam, menit, dan detik
    var timeArray = duration.split(':');
    var hours = parseInt(timeArray[0], 10); // Parse sebagai angka desimal
    var minutes = parseInt(timeArray[1], 10);
    var seconds = parseInt(timeArray[2], 10);

    // Hitung total detik
    var totalSeconds = hours * 3600 + minutes * 60 + seconds;

    // Hitung mundur durasi tes
    var countdown = setInterval(function() {
        // Hitung jam, menit, dan detik yang tersisa
        var hours = Math.floor(totalSeconds / 3600);
        var minutes = Math.floor((totalSeconds % 3600) / 60);
        var seconds = totalSeconds % 60;

        // Format ulang waktu menjadi HH:MM:SS
        var formattedTime = (hours < 10 ? '0' : '') + hours + ':' + (minutes < 10 ? '0' : '') + minutes + ':' + (seconds < 10 ? '0' : '') + seconds;

        // Tampilkan waktu mundur di dalam elemen dengan id "countdownTimer"
        document.getElementById('countdownTimer').innerText = formattedTime;

        // Kurangi total detik dengan 1 setiap detik
        totalSeconds--;

        // Simpan durasi tersisa ke localStorage
        localStorage.setItem('remainingDuration', formattedTime);

        // Jika waktu sudah habis, hentikan hitungan mundur
        if (totalSeconds < 0) {
            clearInterval(countdown);
            // Otomatis submit form
            document.getElementById('answerForm').submit();
            // Hapus durasi tersisa dari localStorage
            localStorage.removeItem('remainingDuration');
        }
    }, 1000);


</script>

@endsection
