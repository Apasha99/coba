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
    <div class="mb-4 col-span-full xl:mb-2">
        <h3 class="text-l font-semibold text-gray-900 sm:text-l dark:text-white">Add Submission</h3>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <form action="{{ route('peserta.updateSubmission', [$pelatihan->kode, $tugas->id, $submission->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="col-span-full">
                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="poster">Upload File </label>
                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                    aria-describedby="file_input_help" id="submission_files" name="submission_files[]" type="file" accept=".pdf, .doc, .docx, .xls, .xlsx, image/*" multiple>
                @error('submission_files')
                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400" role="alert">
                        <div>
                            {{ $message }}
                        </div>
                    </div>
                @enderror
            </div>
            <div class="col-span-6 sm:col-full mt-4">
                <button
                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                    type="submit">Submit</button>
            </div>
        </form>
    </div>
@endsection