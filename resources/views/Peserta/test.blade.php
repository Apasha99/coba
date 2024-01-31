@extends('peserta.layout.layout2')

@section('content')
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h3 class="text-center text-lg font-bold text-gray-900 sm:text-lg dark:text-white mb-2">{{$test->nama}}</h3>

    {{-- Navigation buttons for each question --}}
    <div class="mb-4">
        @foreach ($soal_test as $soal)
            <a href="{{ route('peserta.test', ['plt_kode' => $test->plt_kode, 'test_id' => $test->id, 'question_number' => $loop->iteration]) }}" class="px-4 py-2 bg-blue-500 text-white rounded mr-2 hover:bg-blue-700">
                Soal {{ $loop->iteration }}
            </a>
        @endforeach
    </div>

    @php
        $currentQuestion = $soal_test->first();
        $currentIndex = 1;
    @endphp

    @if($currentQuestion)
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
                        <form method="post" action="{{ route('peserta.submitAnswer', ['plt_kode' => $test->plt_kode, 'test_id' => $test->id]) }}">
                            @csrf
                            <input type="hidden" name="soal_id" value="{{ $soal->id }}">

                            @php
                                // Move the variable declaration outside the loop
                                $randomizedOptions = [];

                                // Get all options for the current question
                                $options = $jawaban_test->where('soal_id', $soal->id)->shuffle();

                                // Assign letters (A, B, C, etc.) to the randomized options
                                foreach ($options as $index => $jawaban) {
                                    $optionLetter = chr(65 + $index);
                                    $randomizedOptions[$optionLetter] = $jawaban;
                                }
                            @endphp

                            @foreach ($randomizedOptions as $optionLetter => $jawaban)
                                <button type="submit" name="selected_option" value="{{ $optionLetter }}">
                                    {{ $optionLetter }}. {{ $jawaban->title }}
                                </button><br>
                            @endforeach
                        </form>
                    </th>
                </tr>

            </tbody>
        </table>
    @endif

    {{-- Previous and Next buttons --}}
    <div class="mt-4">
        @if($currentIndex > 1)
            <a href="{{ route('peserta.test', ['plt_kode' => $test->plt_kode, 'test_id' => $test->id, 'question_number' => $currentIndex - 1]) }}" class="px-4 py-2 bg-blue-500 text-white rounded mr-2 hover:bg-blue-700">
                Previous
            </a>
        @endif

        @if($currentIndex < count($soal_test))
            <a href="{{ route('peserta.test', ['plt_kode' => $test->plt_kode, 'test_id' => $test->id, 'question_number' => $currentIndex + 1]) }}" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-700">
                Next
            </a>
        @endif
    </div>
</div>
@endsection
