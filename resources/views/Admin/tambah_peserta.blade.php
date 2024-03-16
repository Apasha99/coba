@extends('admin.layout.layout')

@section('content')
    <div class="mb-4 col-span-full xl:mb-2">
        <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="/admin/dashboard"
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
                    <a href="{{route('admin.viewDaftarPeserta')}}"
                        class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Daftar Peserta
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
                        <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Tambah Peserta</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Tambah Peserta</h1>
    </div>
    <!-- Right Content -->
    <div class="col-span-full xl:col-auto">
        <form action="{{ route('admin.storePeserta') }}" method="post"  enctype="multipart/form-data">
            @csrf
        </div>
        <div class="col-span-4">
                <div
                    class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                    <h3 class="mb-4 text-xl font-semibold dark:text-white">Data Peserta</h3>
                    
                        <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="nama"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama" placeholder="Nama" id="nama"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        required>
                                    @error('nama')
                                    <div class="mt-2 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400"
                                        role="alert">
                                        <div>
                                            {{ $message }}
                                        </div>
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="username"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Username <span class="text-red-500">*</span></label>
                                    <input type="text" name="username" placeholder="Username" id="username"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        required>
                                    @error('username')
                                    <div class="mt-2 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400"
                                        role="alert">
                                        <div>
                                            {{ $message }}
                                        </div>
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="email"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Email <span class="text-red-500">*</span></label>
                                    <input type="text" name="email" placeholder="Email" id="email"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        required>
                                    @error('email')
                                    <div class="mt-2 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400"
                                        role="alert">
                                        <div>
                                            {{ $message }}
                                        </div>
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="noHP"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nomor Handphone <span class="text-red-500">*</span></label>
                                    <input type="text" name="noHP" placeholder="Nomor Handphone" id="noHP"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        required>
                                    @error('noHP')
                                    <div class="mt-2 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400"
                                        role="alert">
                                        <div>
                                            {{ $message }}
                                        </div>
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="alamat"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Alamat <span class="text-red-500">*</span></label>
                                    <input type="text" name="alamat" placeholder="Alamat" id="alamat"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        required>
                                    @error('alamat')
                                    <div class="mt-2 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400"
                                        role="alert">
                                        <div>
                                            {{ $message }}
                                        </div>
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-span-full">
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="poster">Upload Foto</label>
                                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="file_input_help" id="foto" name="foto" type="file" accept="image/*">
                                    @error('foto')
                                    <div class="mt-2 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400"
                                        role="alert">
                                        <div>
                                            {{ $message }}
                                        </div>
                                    </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-span-6 sm:col-full mt-4">
                                <button
                                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                    type="submit">Simpan</button>
                            </div>
                        </div>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection