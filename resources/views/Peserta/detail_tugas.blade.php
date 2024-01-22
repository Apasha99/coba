@extends('peserta.layout.layout')

@section('content')
    <div class="mb-4 col-span-full xl:mb-2">
        <h1 class="text-2xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ $tugas->judul }}</h1>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <p class="text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Start date: 
            <span class="text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{ \Carbon\Carbon::parse($tugas->start_date)->format('l, j F Y, h:i A') }}
            </span> 
        </p>
        <p class="text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            End date: 
            <span class="text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{ \Carbon\Carbon::parse($tugas->end_date)->format('l, j F Y, h:i A') }}
            </span> 
        </p>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <h3 class="text-l font-semibold text-gray-900 sm:text-l dark:text-white">Deskripsi Penugasan</h3>
        <p class="text-sm font-normal text-black-500 dark:text-gray-400">
            {!! nl2br(e($tugas->deskripsi)) !!}
        </p>
    </div>
    @if($submission)
    <div class="mb-4 col-span-full xl:mb-2">
        <a type="button" href="{{ route('peserta.viewSubmissionForm', [$pelatihan->kode, $tugas->id]) }}"
            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            Edit Submission
        </a>
        <a type="button" href="{{ route('peserta.viewSubmissionForm', [$pelatihan->kode, $tugas->id]) }}"
            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center rounded-lg text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 sm:w-auto focus:ring-4 focus:ring-gray-200 font-medium text-sm dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600 dark:focus:ring-gray-700">
            Remove Submission
        </a>
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
            Add Submission
        </a>
    </div>
    @endif
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <tbody>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Submission status
                </th>
                <td class="px-6 py-4">
                    @if($submission)
                        Submitted for grading
                    @else
                        No submissions have been made yet
                    @endif
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Grading status
                </th>
                <td class="px-6 py-4">
                    @if($submission)
                        @if($submission->grading_status == "not graded")
                            Not graded
                        @else
                            Graded
                        @endif
                    @else
                        Not graded
                    @endif
                </td>
            </tr>
            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Time remaining
                </th>
                <td class="px-6 py-4">
                @php
                    $endDateTime = \Carbon\Carbon::parse($tugas->end_date);
                    $diff = now()->diff($endDateTime);
                    $now = now()
                @endphp
                @if($submission)
                    @if($submission->created_at < $tugas->end_date)
                        Assignment was submitted {{ $diff->days }} days {{ $diff->h }} hours {{ $diff->i }} minutes early
                    @elseif($submission->created_at > $tugas->end_date)
                        <span style="color: red;">Assignment was submitted late by: {{ $diff->days }} days {{ $diff->h }} hours {{ $diff->i }} minutes</span>
                    @endif
                @else
                    @if($now < $tugas->end_date)
                        {{ $diff->days }} days {{ $diff->h }} hours {{ $diff->i }} minutes
                    @else
                        <span style="color: red;">Assignment is overdue by: {{ $diff->days }} days {{ $diff->h }} hours {{ $diff->i }} minutes</span>
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






