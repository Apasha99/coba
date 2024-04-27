@extends('instruktur.layout.layout_tabs')
<link
	href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
	rel="stylesheet">
@section('tabs')
<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px">
        <li class="me-2">
            <a href="{{ route('instruktur.viewDetailPelatihan', $pelatihan->kode) }}" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">Pelatihan</a>
        </li>
        <li class="me-2">
            <a href="{{ route('instruktur.viewDaftarPartisipan', $pelatihan->kode) }}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Partisipan</a>
        </li>
        <li class="me-2">
            <a href="{{ route('test.rekap', ['plt_kode' => $pelatihan->kode]) }}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Rekap Test</a>
        </li>
    </ul>
</div>
@section('content')
    <div class="mb-2 col-span-full xl:mb-2">
    <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="/instruktur/dashboard"
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
                    <a href="{{route('instruktur.viewDaftarPelatihan')}}"
                        class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Daftar Pelatihan
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
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ $pelatihan->nama }}</h1>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <p class="text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Bidang Penyelanggara: 
            <span class="text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{ $bidang }}
            </span> 
        </p>
        <p class="text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Start date: 
            <span class="mt-2 text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{ \Carbon\Carbon::parse($pelatihan->start_date)->format('l, j F Y, h:i A') }}
            </span> 
        </p>
        <p class="text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            End date: 
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
   
    <div class="mb-4 col-span-full xl:mb-2">
    @if ($pelatihan->status != 'Completed')
        <a type="button" href="{{ route('instruktur.viewTambahMateri', [$pelatihan->kode]) }}"
            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                    clip-rule="evenodd"></path>
            </svg>
            Tambah Materi
        </a>
    @endif
    </div>
    <div id="accordion-open" data-accordion="open">
        <h2 id="accordion-open-heading-materi">
            <button type="button" class="flex items-center justify-between w-full p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-lg focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-open-body-materi" aria-expanded="true" aria-controls="accordion-open-body-materi">
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
                <!-- <a href="#" class="flex items-center text-m font-semibold leading-tight tracking-tight text-gray-900 md:text-m dark:text-white">View Details</a> -->
                @if ($pelatihan->status != 'Completed')
                <div>
                    <a data-popover-target="popover-edit-{{ $mtr->id }}" href="{{ route('instruktur.viewEditMateri', [$pelatihan->kode, $mtr->id]) }}" class="text-blue-400 hover:text-blue-100 mx-2">
                        <i class="material-icons-outlined text-base">edit</i>
                    </a>
                    <div data-popover id="popover-edit-{{ $mtr->id }}" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-gray-500 duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                        <div class="px-3 py-2">
                            <p>Edit</p>
                        </div>
                    </div>
                    <a data-popover-target="popover-delete-{{ $mtr->id }}" href="#" data-modal-target="delete-modal-materi-{{ $mtr->id }}" data-modal-toggle="delete-modal-materi-{{ $mtr->id }}" class="text-red-400 hover:text-red-100 ml-2">
                        <i class="material-icons-round text-base">delete_outline</i>
                    </a>
                    <div data-popover id="popover-delete-{{ $mtr->id }}" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-gray-500 duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                        <div class="px-3 py-2">
                            <p>Delete</p>
                        </div>
                    </div>
                    <div id="delete-modal-materi-{{ $mtr->id }}" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                        <div class="relative p-4 w-full max-w-md max-h-full">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="delete-modal-materi-{{ $mtr->id }}">
                                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                    </svg>
                                    <span class="sr-only">Close modal</span>
                                </button>
                                <div class="p-4 md:p-5 text-center">
                                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                    </svg>
                                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus materi {{$mtr->judul}}?</h3>
                                    <form action="{{ route('materi.delete', [$pelatihan->kode, $mtr->id]) }}" method="post">
                                        @csrf
                                        <button data-modal-hide="delete-modal-materi-{{ $mtr->id }}" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                                            Ya
                                        </button>
                                        <button data-modal-hide="delete-modal-materi-{{ $mtr->id }}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Tidak</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        @endforeach
    </div>
    <div class="mb-2 mt-6 col-span-full xl:mb-2">
        @if ($pelatihan->status != 'Completed')
        <a type="button" href="{{ route('instruktur.viewTambahTugas', [$pelatihan->kode]) }}"
            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                    clip-rule="evenodd"></path>
            </svg>
            Tambah Tugas
        </a>
        @endif
    </div>
    <div id="accordion-open" data-accordion="open">
        <h2 id="accordion-open-heading-tugas">
            <button type="button" class="flex items-center justify-between w-full mt-4 p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-lg focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-open-body-tugas" aria-expanded="true" aria-controls="accordion-open-body-tugas">
            <span class="flex items-center text-l font-semibold leading-tight tracking-tight text-gray-900 md:text-xl dark:text-white"> Tugas </span>
            <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
            </svg>
            </button>
        </h2>
        <div id="accordion-open-body-tugas" class="hidden" aria-labelledby="accordion-open-heading-tugas">
            @foreach ($tugas as $tgs)
                <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900 flex justify-between">
                    <a href="{{ route('instruktur.viewDaftarSubmissionTugas', [$pelatihan->kode, $tgs->id]) }}" class="flex items-center text-m font-semibold leading-tight tracking-tight text-blue-500 md:text-m dark:text-blue-500 hover:underline">{{ $tgs->judul }}</a>
                    @if ($pelatihan->status != 'Completed')
                    <div>
                        <a data-popover-target="popover-edit-{{ $tgs->id }}" href="{{ route('instruktur.viewEditTugas', [$pelatihan->kode, $tgs->id]) }}" class="text-blue-400 hover:text-blue-100 mx-2">
                            <i class="material-icons-outlined text-base">edit</i>
                        </a>
                        <div data-popover id="popover-edit-{{ $tgs->id }}" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-gray-500 duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                            <div class="px-3 py-2">
                                <p>Edit</p>
                            </div>
                        </div>
                        <a data-popover-target="popover-delete-{{ $tgs->id }}" href="#" data-modal-target="delete-modal-tugas-{{ $tgs->id }}" data-modal-toggle="delete-modal-tugas-{{ $tgs->id }}" class="text-red-400 hover:text-red-100 ml-2">
                            <i class="material-icons-round text-base">delete_outline</i>
                        </a>
                        <div data-popover id="popover-delete-{{ $tgs->id }}" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-gray-500 duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                            <div class="px-3 py-2">
                                <p>Delete</p>
                            </div>
                        </div>
                        <div id="delete-modal-tugas-{{ $tgs->id }}" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-md max-h-full">
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="delete-modal-tugas-{{ $tgs->id }}">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                    <div class="p-4 md:p-5 text-center">
                                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus tugas {{$tgs->judul}}?</h3>
                                        <form action="{{ route('tugas.delete', [$pelatihan->kode, $tgs->id]) }}" method="post">
                                            @csrf
                                            <button data-modal-hide="delete-modal-tugas-{{ $tgs->id }}" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                                                Ya
                                            </button>
                                            <button data-modal-hide="delete-modal-tugas-{{ $tgs->id }}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Tidak</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    <div class="mb-2 mt-6 col-span-full xl:mb-2">
        @if ($pelatihan->status != 'Completed')
        <a type="button" href = "{{route('test.add', $pelatihan->kode)}}"
            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                    clip-rule="evenodd"></path>
            </svg>
            Tambah Test
        </a>
        @endif
    </div>
    <div id="accordion-open" data-accordion="open">
        <h2 id="accordion-open-heading-test">
            <button type="button" class="flex items-center justify-between w-full mt-4 p-5 font-medium rtl:text-right text-gray-500 border border-b-0 border-gray-200 rounded-t-lg focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-800 dark:border-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 gap-3" data-accordion-target="#accordion-open-body-test" aria-expanded="true" aria-controls="accordion-open-body-test">
                <span class="flex items-center text-l font-semibold leading-tight tracking-tight text-gray-900 md:text-xl dark:text-white"> Test </span>
                <svg data-accordion-icon class="w-3 h-3 rotate-180 shrink-0" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5 5 1 1 5"/>
                </svg>
            </button>
        </h2>
        <div id="accordion-open-body-test" class="hidden" aria-labelledby="accordion-open-heading-test">
            @foreach ($test as $tes)
                <div class="p-5 border border-b-0 border-gray-200 dark:border-gray-700 dark:bg-gray-900 flex justify-between">
                    <div class="flex items-center">
                        <a href="{{ route('test.detail', ['plt_kode' => $tes->plt_kode, 'test_id' => $tes->id]) }}" class="flex items-center text-m font-semibold leading-tight tracking-tight text-blue-500 md:text-m dark:text-blue-500 hover:underline">{{ $tes->nama }}</a>
                        <span class="ml-2 pointer-events-none flex items-center">
                            @php
                                $startDate = new DateTime($tes->start_date);
                                $endDate = new DateTime($tes->end_date);
                                $now = new DateTime();
                            @endphp
                            @if($startDate < $now && $endDate > $now)
                                <span class="bg-green-200 text-green-900 text-sm font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-100">Aktif</span> 
                            @elseif($startDate > $now)
                                <span class="bg-gray-500 text-white text-sm font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Belum mulai</span>
                            @elseif($endDate < $now)
                                <span class="dark:bg-red-900 bg-red-300 text-red-900 text-sm font-medium px-2.5 py-0.5 rounded dark:text-red-200">Selesai</span>
                            @endif 
                        </span>
                    </div>
                    @if ($pelatihan->status != 'Completed')
                    <div>
                        <a data-popover-target="popover-edit-{{ $tes->id }}" href="{{route('test.edit', [$tes->plt_kode, $tes->id])}}" class="text-blue-400 hover:text-blue-100 mx-2">
                            <i class="material-icons-outlined text-base">edit</i>
                        </a>
                        <div data-popover id="popover-edit-{{ $tes->id }}" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-gray-500 duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                            <div class="px-3 py-2">
                                <p>Edit</p>
                            </div>
                        </div>
                        <a data-popover-target="popover-delete-{{ $tes->id }}" href="#" data-modal-target="delete-modal-test-{{ $tes->id }}" data-modal-toggle="delete-modal-test-{{ $tes->id }}" class="text-red-400 hover:text-red-100 ml-2">
                            <i class="material-icons-round text-base">delete_outline</i>
                        </a>
                        <div data-popover id="popover-delete-{{ $tes->id }}" role="tooltip" class="absolute z-10 invisible inline-block text-sm text-gray-500 duration-300 bg-white border border-gray-200 rounded-lg shadow-sm opacity-0 dark:text-gray-400 dark:border-gray-600 dark:bg-gray-800">
                            <div class="px-3 py-2">
                                <p>Delete</p>
                            </div>
                        </div>
                        <div id="delete-modal-test-{{ $tes->id }}" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                            <div class="relative p-4 w-full max-w-md max-h-full">
                                <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                    <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="delete-modal-test-{{ $tes->id }}">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                        </svg>
                                        <span class="sr-only">Close modal</span>
                                    </button>
                                    <div class="p-4 md:p-5 text-center">
                                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                        </svg>
                                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus test {{$tes->nama}}?</h3>
                                        <form action="{{route('test.delete',['plt_kode'=>$tes->plt_kode,'test_id'=>$tes->id])}}" method="post">
                                            @csrf
                                            <button data-modal-hide="delete-modal-test-{{ $tes->id }}" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                                                Ya
                                            </button>
                                            <button data-modal-hide="delete-modal-test-{{ $tes->id }}" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Tidak</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>

@endsection
@endsection






