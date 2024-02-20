@extends('peserta.layout.layout')

@section('content')
    <div class="mb-2 col-span-full xl:mb-2">
        <nav class="flex mb-5" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                    <li class="inline-flex items-center">
                        <a href="/peserta/dashboard"
                            class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                            <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                                </path>
                            </svg>
                            Dashboard
                        </a>
                    </li>
                    <li class="flex items-center">
                        <a href="{{route('peserta.viewDetailPelatihan', $pelatihan->kode)}}"
                            class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Detail Pelatihan
                        </a>
                    </li>
                    <li class="flex items-center">
                        <a href="{{route('peserta.viewDetailTest',[ $pelatihan->kode, $test->id])}}"
                            class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Detail Test
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Hasil Test</span>
                        </div>
                    </li>
                </ol>
            </nav>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <h1 class="text-2xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Hasil Test</h1>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <p class="text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Test: 
            <span class="text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{ $test->nama }}
            </span> 
        </p>
        <p class="mt-2 text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Total Soal: 
            <span class="text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{ $hitungsoal }}
            </span> 
        </p>
        <p class="mt-2 text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Jumlah Benar: 
            <span class="text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{$jawabBenar}}
            </span> 
        </p>
        <p class="mt-2 text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Total Nilai: 
            <span class="text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{$hitungnilai}}
            </span> 
        </p>
        <p class="mt-2 text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            KKM: 
            <span class="text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{$test->kkm}}
            </span> 
        </p>
        @if ($hitungnilai >= $test->kkm)
            <div class="mt-2 p-2 bg-green-200 rounded-lg">
                <p class="text-center text-sm font-semibold text-green-800">
                    Passed
                </p>
            </div>
        @else
            <div class="mt-2 p-2 bg-red-200 rounded-lg">
                <p class="text-center text-sm font-semibold text-red-800">
                    Failed
                </p>
            </div>
        @endif
    </div>
    @if ($test->tampil_hasil == true)
        <div class="mb-4 col-span-full xl:mb-2">
            <h1 class="text-2xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Review Test</h1>
        </div>
        @php
            $shuffledSoalTest = $soal_test->shuffle();
        @endphp
        @foreach ($soal_test as $soal)

        <div class="flex">
            
            <table class="w-3/4 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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
                                $shuffledOrder = collect($originalOrder)->shuffle(1);
                                $selectedOption = old('selected_option_' . $soal->urutan) ?? session('selected_option_' . $soal->urutan) ?? null;
                                $latestAttempt = DB::table('peserta')
                                    ->join('nilai_test', 'nilai_test.peserta_id', '=', 'peserta.id')
                                    ->where('peserta.user_id', Auth::user()->id)
                                    ->where('nilai_test.test_id', $test->id)
                                    ->max('nilai_test.attempt');
                                $jawabPilgan = DB::table('jawaban_user_pilgan')
                                    ->join('nilai_test', 'jawaban_user_pilgan.test_id', '=', 'nilai_test.test_id')
                                    ->join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
                                    ->where('peserta.user_id', Auth::user()->id) // Memastikan hanya data pengguna saat ini yang diambil
                                    ->where('jawaban_user_pilgan.test_id', $test->id)
                                    ->whereColumn('jawaban_user_pilgan.peserta_id', '=', 'nilai_test.peserta_id') // Menggunakan whereColumn untuk kondisi join
                                    ->where('jawaban_user_pilgan.soal_id', $soal->id)
                                    ->where('jawaban_user_pilgan.attempt', $latestAttempt)
                                    ->get();

                            @endphp

                            @foreach ($shuffledOrder as $index => $shuffledIndex)
                                @php
                                    $jawaban = $options->where('urutan', $shuffledIndex)->first();
                                    $isChecked = $jawabPilgan->contains('jawaban_id', $jawaban->id);
                                @endphp
                                <label>
                                    <input disabled id="ganda-{{ $soal->urutan }}-{{ $index }}" type="radio" name="selected_option[{{ $soal->urutan }}]" value="{{ $jawaban->id }}" onclick="handleRadioClick({{ $soal->urutan }}, '{{ $jawaban->id }}')" {{ $isChecked ? 'checked' : '' }}>
                                    {{ $jawaban->title }}
                                </label><br>
                            @endforeach

                            @elseif ($soal->tipe == "Jawaban Singkat") 
                                @php
                                    $latestAttempt = DB::table('peserta')
                                        ->join('nilai_test', 'nilai_test.peserta_id', '=', 'peserta.id')
                                        ->where('peserta.user_id', Auth::user()->id)
                                        ->where('nilai_test.test_id', $test->id)
                                        ->max('nilai_test.attempt');
                                    $jawabSingkat = DB::table('jawaban_user_singkat')
                                        ->join('nilai_test', 'jawaban_user_singkat.test_id', '=', 'nilai_test.test_id')
                                        ->join('peserta', 'peserta.id', '=', 'nilai_test.peserta_id')
                                        ->where('peserta.user_id', Auth::user()->id)
                                        ->where('jawaban_user_singkat.test_id', $test->id)
                                        ->where('jawaban_user_singkat.soal_id', $soal->id)
                                        ->where('jawaban_user_singkat.attempt', $latestAttempt)
                                        ->whereColumn('jawaban_user_singkat.peserta_id', '=', 'nilai_test.peserta_id')
                                        ->first();
                                @endphp
                                <input disabled type="text" name="singkat[{{ $soal->urutan }}]" placeholder="Jawaban" id="singkat-{{ $soal->urutan }}"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    value="{{ $jawabSingkat->jawaban }}" required>
                                
                            @endif
                        </th>
                    </tr>
                </tbody>
            </table>
            
            
            <div class="ml-4 w-1/4">
                <div class="mb-2 col-span-full xl:mb-2">
                    <h3 class="text-2xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Nilai</h3>
                </div>
                <table class="mb-4 text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <tbody>
                        <tr class="bg-white dark:bg-gray-900 dark:border-gray-900 hover:bg-gray-50 dark:hover:bg-gray-900">
                            <th scope="row" class="py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Nilai yang diperoleh : {{ $nilai->where('soal_id', $soal->id)->first()->nilai != 0 ? '1.0' : '0.0' }} / 1.0
                            </th>
                        </tr>
                    </tbody>
                </table>
                <div class="mb-2 col-span-full xl:mb-2">
                    <h3 class="text-2xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Jawaban Benar</h3>
                </div>
                <table class="text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <tbody>
                        @if ($soal->tipe == "Pilihan Ganda")
                        <tr class="bg-white dark:bg-gray-900 dark:border-gray-900 hover:bg-gray-50 dark:hover:bg-gray-900">
                            <th scope="row" class="py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Jawaban : {{ $jawaban_test->where('soal_id', $soal->id)->where('status', 1)->first()->title }}
                            </th>
                        </tr>
                        @elseif ($soal->tipe == "Jawaban Singkat")
                        <tr class="bg-white dark:bg-gray-900 dark:border-gray-900 hover:bg-gray-50 dark:hover:bg-gray-900">
                            <th scope="row" class="py-2 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                Jawaban : {{ $jawaban_test->where('soal_id', $soal->id)->pluck('title')->implode(', ') }}

                            </th>
                        </tr>
                        @endif
                    </tbody>
                </table>
                
            </div>
            
        </div>


            
        @endforeach
    @endif


    
@endsection






