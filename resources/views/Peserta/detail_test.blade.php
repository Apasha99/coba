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
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Detail Test</span>
                        </div>
                    </li>
                </ol>
            </nav>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <h1 class="text-2xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ $test->nama }}</h1>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <p class="text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Tanggal Mulai: 
            <span class="text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{ \Carbon\Carbon::parse($test->start_date)->format('l, j F Y, h:i A') }}
            </span> 
        </p>
        <p class="mt-2 text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Tanggal Selesai: 
            <span class="text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{ \Carbon\Carbon::parse($test->end_date)->format('l, j F Y, h:i A') }}
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
    <div class="grid grid-cols-6 gap-6 overflow-x-auto shadow-md sm:rounded-lg">
        <div class="p-2 mb-4 col-span-6 sm:col-span-3 xl:mb-2">
            <h3 class="text-l font-semibold text-gray-900 sm:text-l dark:text-white">Deskripsi Test</h3>
            <p class="text-sm font-normal text-black-500 dark:text-gray-400">
                {!! nl2br(e($test->deskripsi)) !!}
            </p>
        </div>
        <div class="p-2 mb-4 col-span-6 sm:col-span-3 xl:mb-2">
            <h3 class="text-l font-semibold text-gray-900 sm:text-l dark:text-white">Durasi</h3>
            <p class="text-sm font-normal text-black-500 dark:text-gray-400">
            @if (\Carbon\Carbon::parse($test->durasi)->format('H') != '00')
                {{ \Carbon\Carbon::parse($test->durasi)->format('H') }} Jam
            @elseif (\Carbon\Carbon::parse($test->durasi)->format('i') != '00')
                {{ \Carbon\Carbon::parse($test->durasi)->format('i') }} Menit
            @elseif (\Carbon\Carbon::parse($test->durasi)->format('s') != '00')
                {{ \Carbon\Carbon::parse($test->durasi)->format('s') }} Detik
            @endif
            </p>
        </div>
        @if ($existingNilai)
        <div class="p-2 mb-4 col-span-6 sm:col-span-3 xl:mb-2">
            <div class="mb-4 col-span-full xl:mb-2">
                <h3 class="text-xl font-semibold text-gray-900 sm:text-xl dark:text-white">Hasil Test</h3>
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
            </div>
        </div>
        @endif
    </div>
    <div class="col-span-full mt-4 mb-4 text-center">
        
        <a type="button" href="{{ route('peserta.test', ['plt_kode' => $pelatihan->kode, 'test_id' => $test->id]) }}"
            class="inline-flex items-center justify-center w-1/2 px-12 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            Mulai
        </a>
        
    </div>
    
@endsection






