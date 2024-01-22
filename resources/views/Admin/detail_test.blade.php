@extends('admin.layout.layout')

@section('content')
    <div class="mb-4 col-span-full xl:mb-2">
        <h1 class="text-2xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ $test->nama }}</h1>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <p class="text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Start date: 
            <span class="text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{ \Carbon\Carbon::parse($test->start_date)->format('l, j F Y, h:i A') }}
            </span> 
        </p>
        <p class="mt-2 text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            End date: 
            <span class="text-sm font-normal text-gray-900 sm:text-sm dark:text-white">
                {{ \Carbon\Carbon::parse($test->end_date)->format('l, j F Y, h:i A') }}
            </span> 
        </p>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <a type="button" href=""
            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                    clip-rule="evenodd"></path>
            </svg>
            Tambah Soal
        </a>
    </div>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <tbody>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Submission status
                </th>
                <td class="px-6 py-4">
                    Silver
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Grading status
                </th>
                <td class="px-6 py-4">
                    White
                </td>
            </tr>
            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Time remaining
                </th>
                <td class="px-6 py-4">
                    Black
                </td>
            </tr>
        </tbody>
    </table>
</div>
@endsection






