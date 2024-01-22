@extends('peserta.layout.layout')

@section('content')
    <div class="mb-4 col-span-full xl:mb-2">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ $pelatihan->nama }}</h1>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <h3 class="text-l font-semibold text-gray-900 sm:text-l dark:text-white">Description</h3>
        <p class="text-sm font-normal text-gray-500 truncate dark:text-gray-400">
            {{ $pelatihan->deskripsi }}
        </p>
    </div>
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
                <h3 class="flex items-center text-m font-semibold leading-tight tracking-tight text-gray-900 md:text-m dark:text-white">{{ $mtr->judul }}</h3>
                <a href="#" class="flex items-center text-m font-semibold leading-tight tracking-tight text-gray-900 md:text-m dark:text-white">View Details</a>
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
            @foreach ($tugas as $tgs)
                <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900 flex justify-between">
                    <a href="{{ route('peserta.viewDetailTugas', [$pelatihan->kode, $tgs->id]) }} }}" class="flex items-center text-m font-semibold leading-tight tracking-tight text-blue-500 md:text-m dark:text-blue-500 hover:underline">{{ $tgs->judul }}</a>
                </div>
            @endforeach
        </div>
    </div>
    <div id="accordion-open" data-accordion="open">
        <h2 id="accordion-open-heading-test">
            <button type="button" class="flex items-center justify-between w-full mt-4 p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-xl focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-open-body-tugas" aria-expanded="true" aria-controls="accordion-open-body-tugas">
            <span class="flex items-center text-l font-semibold leading-tight tracking-tight text-gray-900 md:text-xl dark:text-white"> Test </span>
            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
            </svg>
            </button>
        </h2>
        <div id="accordion-open-body-test" class="hidden" aria-labelledby="accordion-open-heading-test">
            @foreach ($test as $tes)
                <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900 flex justify-between">
                    <a href="#" class="flex items-center text-m font-semibold leading-tight tracking-tight text-blue-500 md:text-m dark:text-blue-500 hover:underline">{{ $tgs->judul }}</a>
                </div>
            @endforeach
        </div>
    </div>
@endsection






