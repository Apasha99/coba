@extends('peserta.layout.layout2')

@section('content')
<div class="fixed top-0 left-0 w-full bg-white z-50 p-4 shadow-md">
    <h3 class="text-center justify-between text-lg font-bold text-gray-900 sm:text-lg dark:text-white mb-2">{{$test->nama}}</h3>
    <p class="dark:text-white justify-between">Durasi Tes: <span id="countdownTimer" class="dark:text-white">{{$test->durasi}}</span></p>
</div>
<div class="relative justify-between shadow-md sm:rounded-lg">
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
                                <img class="w-80 h-30" src="{{ asset('storage/' . $soal->file_soal) }}" alt="{{ $soal->urutan }}">
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
                                    value="{{ old('singkat_' . $soal->urutan) ?? session('singkat_' . $soal->urutan) }}" required>


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
        <div class="float-right col-span-6 sm:col-full mt-4 mb-4">
            <button
                class="mr-4 text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                type="submit">Submit</button>
        </div>

    </form>
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

        // Jika waktu sudah habis, hentikan hitungan mundur
        if (totalSeconds < 0) {
            clearInterval(countdown);
            // Otomatis submit form
            document.getElementById('answerForm').submit();
        }
    }, 1000);

</script>

@endsection
