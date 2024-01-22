d@extends('admin.layout.layout')

@section('content')
    <div class="mb-2 col-span-full xl:mb-2">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">abc</h1>
    </div>
    <div class="mb-2 col-span-full xl:mb-2">
    <p class="text-sm font-normal text-gray-500 truncate dark:text-gray-400">
            abc
        </p>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <h3 class="text-l font-semibold text-gray-900 sm:text-l dark:text-white">Description</h3>
        <p class="text-sm font-normal text-gray-500 truncate dark:text-gray-400">
           def
        </p>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
            <button type="button" data-modal-toggle="add-materi-modal"
                class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd"></path>
                </svg>
                Add Materi
            </button>
        </div>
    
@endsection






