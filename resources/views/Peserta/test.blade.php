@extends('peserta.layout.layout2')

@section('content')
<div class="relative justify-between overflow-x-auto shadow-md sm:rounded-lg">
    <h3 class="text-center text-lg font-bold text-gray-900 sm:text-lg dark:text-white mb-2">{{$test->nama}}</h3>

    {{-- Navigation buttons for each question --}}
    <div class="p-4 w-1/4 float-right col-span-1 justify-end grid grid-cols-3 gap-2">
        @foreach ($soal_test as $soal)
            <a href="{{ route('peserta.test', ['plt_kode' => $test->plt_kode, 'test_id' => $test->id, 'soal_id' => $soal->id]) }}" class="p-4 px-4 py-2 bg-gray-500 text-sm text-white rounded mb-2 hover:bg-gray-700">
                Soal {{ $soal->urutan }}
            </a>
        @endforeach
    </div>

    @php
        $currentIndex = $currentQuestion->urutan;
    @endphp

    @if($currentQuestion)
    <form id="answerForm" method="post" action="{{ route('peserta.submitAnswer', ['plt_kode' => $test->plt_kode, 'test_id' => $test->id, 'question_id' => $currentQuestion->id]) }}" >
        @csrf
        <input type="hidden" name="current_question_id" value="{{ $currentQuestion->id }}">
        <table class="w-3/4 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Soal No. {{ $currentIndex }}
                    </th>
                </tr>
                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        {{ $currentQuestion->title }}
                    </th>
                </tr>
                <tr class="bg-white dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        @if($currentQuestion->file_soal)
                            <img class="w-80 h-30" src="{{ asset('storage/' . $currentQuestion->file_soal) }}" alt="{{ $currentQuestion->urutan }}">
                        @else
                            <div></div> <!-- Placeholder jika tidak ada gambar -->
                        @endif
                    </th>
                </tr>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        @if ($currentQuestion->tipe == "Pilihan Ganda")
                            @php
                                // Get all options for the current question
                                $options = $jawaban_test->where('soal_id', $currentQuestion->id);
                                $originalOrder = $options->pluck('urutan')->toArray();
                                $shuffledOrder = collect($originalOrder)->shuffle(1);
                                $selectedOption = old('selected_option_' . $currentIndex) ?? session('selected_option_' . $currentIndex) ?? null;
                            @endphp

                            <input type="hidden" name="soal_id_{{ $currentIndex }}" value="{{ $currentQuestion->id }}">
                            <!-- Add these hidden fields inside the form -->
                            <input type="hidden" name="selected_option_{{ $currentIndex }}" id="hiddenSelectedOption" value="{{ $selectedOption }}">
                            <input type="hidden" name="jawaban_singkat_{{ $currentIndex }}" id="hiddenJawabanSingkat" value="{{ old('singkat_' . $currentIndex) ?? session('singkat_' . $currentIndex) }}">

                            @foreach ($shuffledOrder as $index => $shuffledIndex)
                                @php
                                    $jawaban = $options->where('urutan', $shuffledIndex)->first();
                                @endphp
                                <label>
                                    <input id="ganda-{{ $currentIndex }}-{{ $index }}" type="radio" name="selected_option_{{ $currentIndex }}" value="{{ $jawaban->id }}" onclick="handleRadioClick({{ $currentIndex }}, '{{ $jawaban->id }}')" {{ ($jawaban->id == $selectedOption) ? 'checked' : '' }}>
                                    {{ $jawaban->title }}
                                </label><br>
                            @endforeach

                            <script>
                                    // JavaScript to handle saving and retrieving selected option using localStorage
                                    document.addEventListener('DOMContentLoaded', function () {
                                        var form = document.getElementById('answerForm');

                                        // Add event listener to the form to clear localStorage when the form is submitted
                                        form.addEventListener('submit', function () {
                                            localStorage.removeItem('ganda-{{ $currentIndex }}');
                                        });
                                    });

                                    function handleRadioClick(currentQuestionId, selectedOptionId) {
                                        localStorage.setItem('ganda-' + currentQuestionId, selectedOptionId);
                                    }

                                    // Retrieve and set the selected option when the page loads
                                    var storedOption = localStorage.getItem('ganda-{{ $currentIndex }}');
                                    if (storedOption) {
                                        document.querySelector('input[name="selected_option_{{ $currentIndex }}"][value="' + storedOption + '"]').checked = true;
                                    }
                                </script>

                        @elseif ($currentQuestion->tipe == "Jawaban Singkat")
                            <input type="text" name="singkat_{{ $currentIndex }}" placeholder="Jawaban" id="singkat-{{ $currentIndex }}"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                value="{{ old('singkat_' . $currentIndex) ?? session('singkat_' . $currentIndex) }}" required>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var jawabanInput = document.getElementById('singkat-{{ $currentIndex }}');
                                    var form = document.getElementById('answerForm');
                                    var hiddenJawabanSingkat = document.getElementById('hiddenJawabanSingkat');

                                    jawabanInput.addEventListener('input', function () {
                                        localStorage.setItem('jawaban-{{ $currentIndex }}', this.value);
                                        hiddenJawabanSingkat.value = this.value; // Update the hidden field
                                    });

                                    // Your existing short answer input change handling logic
                                    jawabanInput.addEventListener('input', function () {
                                        // Ganti localStorage dengan mengirimkan data ke server menggunakan AJAX
                                        axios.post('/save-answer', {
                                            currentQuestionId: {{ $currentQuestion->id }},
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
                                    var storedJawaban = localStorage.getItem('jawaban-{{ $currentIndex }}');
                                    if (storedJawaban) {
                                        jawabanInput.value = storedJawaban;
                                        hiddenJawabanSingkat.value = storedJawaban; // Update the hidden field
                                    }

                                    // Add event listener to the form to clear localStorage when the form is submitted
                                    form.addEventListener('submit', function () {
                                        localStorage.removeItem('jawaban-{{ $currentIndex }}');
                                    });
                                });
                            </script>
                        @endif
                        @if($currentIndex == count($soal_test))
                            <div class="float-right col-span-6 sm:col-full mt-4">
                                <button
                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800"
                                    type="submit">Submit</button>
                            </div>
                        @endif
                    </th>
                </tr>
            </tbody>
        </table>
    </form>
@endif


    {{-- Previous and Next buttons --}}
    <div class="mt-4 mb-4 flex justify-end w-3/4">
        @if($currentIndex > 1)
            <a href="{{ route('peserta.test', ['plt_kode' => $pelatihan->kode, 'test_id' => $test->id, 'soal_id' => $soal_test->where('urutan', $currentIndex - 1)->first()->id]) }}" class="px-5 py-2 text-m bg-blue-500 text-white rounded mr-2 hover:bg-blue-700">
                Previous
            </a>
        @endif

        @if($currentIndex < count($soal_test))
            <a href="{{ route('peserta.test', ['plt_kode' => $pelatihan->kode, 'test_id' => $test->id, 'soal_id' => $soal_test->where('urutan', $currentIndex + 1)->first()->id]) }}" class="ml-4 px-5 text-m py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                Next
            </a>
        @endif


    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var form = document.getElementById('answerForm');
        var currentQuestionId = {{ $currentQuestion->id }};

        // Function to send data to the server for autosave
        function autosave() {
            var formData = new FormData(form);

            // Send data to the server using AJAX
            axios.post('{{ route('peserta.autosave') }}', formData)
                .then(response => {
                    console.log(response.data);
                })
                .catch(error => {
                    console.error(error);
                });
        }

        // Autosave every 1 second (adjust as needed)
        setInterval(autosave, 1000);

        // Retrieve and set the selected option when the page loads
        var storedOption = localStorage.getItem('ganda-' + currentQuestionId);
        if (storedOption) {
            document.querySelector('input[name="selected_option_' + currentQuestionId + '"][value="' + storedOption + '"]').checked = true;
        }

        // Retrieve and set the entered text when the page loads
        var storedJawaban = localStorage.getItem('jawaban-' + currentQuestionId);
        if (storedJawaban) {
            document.getElementById('singkat-' + currentQuestionId).value = storedJawaban;
        }

        // Add event listener to the form to clear localStorage when the form is submitted
        form.addEventListener('submit', function () {
            localStorage.removeItem('ganda-' + currentQuestionId);
            localStorage.removeItem('jawaban-' + currentQuestionId);
        });
    });

    function handleRadioClick(currentQuestionId, selectedOptionId) {
        localStorage.setItem('ganda-' + currentQuestionId, selectedOptionId);
    }

    document.getElementById('singkat-' + currentQuestionId).addEventListener('input', function () {
        localStorage.setItem('jawaban-' + currentQuestionId, this.value);
    });
</script>


@endsection