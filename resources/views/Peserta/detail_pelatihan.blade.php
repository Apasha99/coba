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
                    <li>
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Detail Pelatihan</span>
                        </div>
                    </li>
                </ol>
            </nav>
    </div>
    @php
        $doneCount = 0; 
    @endphp
    <div class="mb-4 col-span-full xl:mb-2">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ $pelatihan->nama }}</h1>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <p class="text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Tanggal Mulai: 
            <span class="mt-2 text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{ \Carbon\Carbon::parse($pelatihan->start_date)->format('l, j F Y, h:i A') }}
            </span> 
        </p>
        <p class="text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Tanggal Selesai: 
            <span class="mt-2 text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{ \Carbon\Carbon::parse($pelatihan->end_date)->format('l, j F Y, h:i A') }}
            </span> 
        </p>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <h3 class="text-l font-semibold text-gray-900 sm:text-l dark:text-white">Deskripsi Pelatihan</h3>
        <p class="text-sm font-normal text-gray-500 truncate dark:text-gray-400">
            {{ $pelatihan->deskripsi }}
        </p>
    </div>
    @if ($completed == 'true')
    <div class="mb-4 col-span-full xl:mb-2">
        <a type="button" href="{{ route('peserta.cetakSertifikat', [$pelatihan->kode, $peserta->id]) }}"
            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            Unduh Sertifikat
        </a>
    </div>
    @endif
    <div id="accordion-open" data-accordion="open">
        <h2 id="accordion-open-heading-materi">
            <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-open-body-materi" aria-expanded="true" aria-controls="accordion-open-body-materi">
            <span class="flex items-center text-l font-semibold leading-tight tracking-tight text-gray-900 md:text-xl dark:text-white"> Materi </span>
            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
            </svg>
            </button>
        </h2>
        <div id="accordion-open-body-materi" class="hidden" aria-labelledby="accordion-open-heading-materi">
        @foreach ($materi as $mtr)
        <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900 flex justify-between">
        <a href="{{ asset('storage/' . $mtr->file_materi) }}" class="flex items-center text-m font-semibold leading-tight tracking-tight text-blue-500 md:text-m dark:text-blue-500 hover:underline">{{ $mtr->judul }}</a>
            <!-- <a href="{{ asset('storage/' . $mtr->file_materi) }}" class="flex items-center text-m font-semibold leading-tight tracking-tight text-gray-900 md:text-m dark:text-white">View Details</a> -->
        </div>
        @endforeach
        </div>
    </div>
    <div id="accordion-open" data-accordion="open">
        <h2 id="accordion-open-heading-tugas">
            <button type="button" class="flex items-center justify-between w-full mt-4 p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-open-body-tugas" aria-expanded="true" aria-controls="accordion-open-body-tugas">
            <span class="flex items-center text-l font-semibold leading-tight tracking-tight text-gray-900 md:text-xl dark:text-white"> Tugas </span>
            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
            </svg>
            </button>
        </h2>
        <div id="accordion-open-body-tugas" class="hidden" aria-labelledby="accordion-open-heading-tugas">
            @foreach ($tugas->where('start_date', '<=', now()) as $tgs)
                <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900 flex justify-between">
                    <a href="{{ route('peserta.viewDetailTugas', [$pelatihan->kode, $tgs->id]) }}" class="flex items-center text-m font-semibold leading-tight tracking-tight text-blue-500 md:text-m dark:text-blue-500 hover:underline">{{ $tgs->judul }}</a>
                    @php
                        $submission = $tgs->submissions()->where('peserta_id', $peserta->id)->first();
                    @endphp
                    @if ($submission)
                    <div class="flex items-center">
                        <div class="flex items-center p-2 mb-4 text-white rounded-lg bg-green-400 dark:bg-green-500 dark:text-white">
                            <svg class="w-4 h-4 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4.7 4.5 9.3-9"/>
                            </svg>
                            <span class="sr-only">Info</span>
                            <div class="ms-2 text-sm font-medium">
                                Done
                            </div>
                        </div>
                        @php
                            $doneCount++; 
                        @endphp
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    <div id="accordion-open" data-accordion="open">
        <h2 id="accordion-open-heading-test">
            <button type="button" class="flex items-center justify-between w-full mt-4 p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-open-body-test" aria-expanded="true" aria-controls="accordion-open-body-test">
            <span class="flex items-center text-l font-semibold leading-tight tracking-tight text-gray-900 md:text-xl dark:text-white"> Test </span>
            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
            </svg>
            </button>
        </h2>
        <div id="accordion-open-body-test" class="hidden" aria-labelledby="accordion-open-heading-test">
            @foreach ($test as $tes)
                <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900 flex justify-between">
                    <a href="{{route('peserta.viewDetailTest',[$pelatihan->kode, $tes->id])}}" class="flex items-center text-m font-semibold leading-tight tracking-tight text-blue-500 md:text-m dark:text-blue-500 hover:underline">{{ $tes->nama }}</a>
                    @if ($totalNilaiTes[$tes->id] >= $tes->kkm)
                    <div class="flex items-center">
                        <div class="flex items-center p-2 mb-4 text-white rounded-lg bg-green-400 dark:bg-green-500 dark:text-white">
                        <svg class="w-4 h-4 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4.7 4.5 9.3-9"/>
                        </svg>
                        <span class="sr-only">Info</span>
                            <div class="ms-2 text-sm font-medium">
                                Passed
                            </div>
                        </div>
                    </div>
                    @elseif ($totalNilaiTes[$tes->id] < $tes->kkm)
                    <div class="flex items-center">
                        <div class="flex items-center p-2 mb-4 text-white rounded-lg bg-red-400 dark:bg-red-500 dark:text-white">
                        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6m0 12L6 6"/>
                        </svg>
                        <span class="sr-only">Info</span>
                            <div class="ms-2 text-sm font-medium">
                                Failed
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
@endsection