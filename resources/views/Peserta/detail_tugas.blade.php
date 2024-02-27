@extends('peserta.layout.layout')

@section('content')
    <div class="mb-4 col-span-full xl:mb-2">
    <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="/dashboardAdmin"
                        class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                        <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg>
                        Home
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
                        <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Detail Tugas</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-2xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ $tugas->judul }}</h1>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <p class="text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Tanggal mulai: 
            <span class="text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{ \Carbon\Carbon::parse($tugas->start_date)->format('l, j F Y, h:i A') }}
            </span> 
        </p>
        <p class="text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Tanggal selesai: 
            <span class="text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{ \Carbon\Carbon::parse($tugas->end_date)->format('l, j F Y, h:i A') }}
            </span> 
        </p>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <h3 class="text-l font-semibold text-gray-900 sm:text-l dark:text-white">Deskripsi Penugasan</h3>
        <p class="text-sm font-normal text-black-500 dark:text-gray-400">
            {!! nl2br(e($tugas->deskripsi)) !!}
            @if($tugas->file_tugas)
            <a href="{{ asset('storage/' . $tugas->file_tugas) }}" target="_blank" class="flex items-center text-m font-semibold leading-tight tracking-tight text-blue-500 md:text-m dark:text-blue-500 hover:underline">{{ $tugas->nama_file }}</a>
            @endif
        </p>
    </div>
    @if($submission)
    <div class="mb-4 col-span-full xl:mb-2">
        <a type="button" href="{{ route('peserta.viewEditSubmission', [$pelatihan->kode, $tugas->id, $submission->id]) }}"
            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            Edit Pengumpulan
        </a>
        <a type="button" data-modal-target="delete-modal-submission" data-modal-toggle="delete-modal-submission" 
            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center rounded-lg text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 sm:w-auto focus:ring-4 focus:ring-gray-200 font-medium text-sm dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
            Hapus Pengumpulan
        </a>
    </div>
    <div id="delete-modal-submission" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="delete-modal-submission">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus submission ini?</h3>
                    <form action="{{ route('peserta.deleteSubmission', [$pelatihan->kode, $tugas->id, $submission->id]) }}" method="post">
                        @csrf
                        <button data-modal-hide="delete-modal-submission" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center me-2">
                            Ya
                        </button>
                        <button data-modal-hide="delete-modal-submission" type="button" class="text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600 dark:focus:ring-gray-600">Tidak</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else 
    <div class="mb-4 col-span-full xl:mb-2">
        <a type="button" href="{{ route('peserta.viewSubmissionForm', [$pelatihan->kode, $tugas->id]) }}"
            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                    clip-rule="evenodd"></path>
            </svg>
            Kumpulkan tugas
        </a>
    </div>
    @endif
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Status pengumpulan
                    </th>
                    <td class="px-6 py-4">
                        @if($submission)
                            Menunggu untuk dinilai
                        @else
                            Belum ada pengumpulan
                        @endif
                    </td>
                </tr>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Status penilaian
                    </th>
                    <td class="px-6 py-4">
                        @if($submission)
                            @if($submission->grading_status == "not graded")
                                Belum dinilai
                            @else
                                Dinilai
                            @endif
                        @else
                            Belum dinilai
                        @endif
                    </td>
                </tr>
                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Sisa waktu
                    </th>
                    <td class="px-6 py-4">
                    @if($submission)
                        @php
                            $endDateTime = \Carbon\Carbon::parse($tugas->end_date);
                            $diff = $submission->updated_at->diff($endDateTime);
                            $now = now();
                        @endphp
                        @if($submission->created_at < $tugas->end_date)
                            Assignment was submitted {{ $diff->days }} days {{ $diff->h }} hours {{ $diff->i }} minutes early
                        @elseif($submission->created_at > $tugas->end_date)
                            <span style="color: red;">Tugas terlambat dikumpulkan selama: {{ $diff->days }} days {{ $diff->h }} hours {{ $diff->i }} minutes</span>
                        @endif
                    @else
                        @php
                            $endDateTime = \Carbon\Carbon::parse($tugas->end_date);
                            $diff = now()->diff($endDateTime);
                            $now = now()
                        @endphp
                        @if($now < $tugas->end_date)
                            {{ $diff->days }} days {{ $diff->h }} hours {{ $diff->i }} minutes
                        @else
                            <span style="color: red;">Tugas sudah melewati batas waktu selama: {{ $diff->days }} days {{ $diff->h }} hours {{ $diff->i }} minutes</span>
                        @endif
                    @endif
                    </td>
                </tr>
                @if($submission)
                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        File submissions
                    </th>
                    <td class="px-6 py-4">
                        <ul>
                            @foreach ($submission->submission_file as $file)
                                <li>
                                    <a href="{{ asset('storage/' . $file->path_file) }}" target="_blank" class="flex items-center text-m font-semibold leading-tight tracking-tight text-blue-500 md:text-m dark:text-blue-500 hover:underline">{{ $file->nama_file }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
@endsection






