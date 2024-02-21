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
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Daftar Instruktur</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Semua Instruktur</h1>
    </div>
    <div class="sm:flex mb-4">
        <div class="items-center hidden mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
            <input type="text" id="searchInput" placeholder="Search" class="mt-2 mb-4 p-2 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"> 
        </div>
        <script>
            // Ambil input elemen pencarian
            const searchInput = document.getElementById('searchInput');

            // Tambahkan event listener untuk input pencarian
            searchInput.addEventListener('input', function(event) {
                const searchText = event.target.value.toLowerCase(); // Ambil teks pencarian dan ubah menjadi lowercase
                const rows = document.querySelectorAll('tbody tr'); // Ambil semua baris dalam tbody

                // Iterasi melalui setiap baris
                rows.forEach(row => {
                    let matchFound = false; // Inisialisasi variabel untuk menandai apakah pencocokan ditemukan dalam baris tertentu

                    // Ambil sel-sel yang ingin dicocokkan (kolom ID, Nama, Username, Email, dll.)
                    const cellsToSearch = row.querySelectorAll('.searchable');

                    // Iterasi melalui setiap sel yang ingin dicocokkan
                    cellsToSearch.forEach(cell => {
                        const text = cell.textContent.toLowerCase(); // Ambil teks dari sel tersebut

                        // Jika teks pencarian cocok dengan teks pada sel, tandai pencocokan ditemukan
                        if (text.includes(searchText)) {
                            matchFound = true;
                        }
                    });

                    // Tampilkan atau sembunyikan baris berdasarkan apakah ada pencocokan ditemukan dalam baris tersebut
                    if (matchFound) {
                        row.style.display = ''; // Tampilkan baris jika ada pencocokan ditemukan
                    } else {
                        row.style.display = 'none'; // Sembunyikan baris jika tidak ada pencocokan ditemukan
                    }
                });
            });
        </script>
        <div class="flex items-center ml-auto space-x-2 sm:space-x-3">
            <a type="button" href="{{ route('admin.viewTambahInstruktur') }}"
                class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd"></path>
                </svg>
                Tambah Instruktur
            </a>
            <a data-modal-toggle="email-instruktur-modal"
                class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                <i class="fa-solid fas fa-envelope fa-lg mr-3"></i>
                Send Mail
            </a>
            <a href="{{route('admin.downloadInstruktur')}}"
                class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                <svg class="w-5 h-5  mr-2 -ml-1 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M13 11.1V4a1 1 0 1 0-2 0v7.1L8.8 8.4a1 1 0 1 0-1.6 1.2l4 5a1 1 0 0 0 1.6 0l4-5a1 1 0 1 0-1.6-1.2L13 11Z" clip-rule="evenodd"/>
                    <path fill-rule="evenodd" d="M9.7 15.9 7.4 13H5a2 2 0 0 0-2 2v4c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-2.4l-2.3 2.9a3 3 0 0 1-4.6 0Zm7.3.1a1 1 0 1 0 0 2 1 1 0 1 0 0-2Z" clip-rule="evenodd"/>
                </svg>
                Download List
            </a>
        </div>
    </div>
    @if (isset($status))
        <div class="alert alert-success">
            {{ $status }}
        </div>
    @endif
    @if (isset($error))
        <div class="alert alert-danger">
            {{ $error }}
        </div>
    @endif
    <div class="bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600" id="myTable">
                                <thead class="bg-purple-600 dark:bg-purple-500">
                                    <tr>
                                        <th scope="col"
                                            class="p-4 text-xs font-large text-left text-white uppercase dark:text-white">
                                            <span style="float: left;">ID</span>
                                            <span id="sortIcon" style="float: left;">
                                                <svg class="ml-2 w-4 h-4 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                                </svg>
                                            </span>
                                            <div style="clear: both;"></div>
                                        </th>
                                        <th scope="col"
                                            class="p-4 text-xs font-large text-left text-white uppercase dark:text-white">
                                            <span style="float: left;">Nama</span>
                                                <span id="sortButton" style="float: left;">
                                                    <svg class="ml-2 w-4 h-4 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                                    </svg>
                                                </span>
                                            <div style="clear: both;"></div>
                                        </th>
                                        <th scope="col"
                                            class="p-4 text-xs font-large text-left text-white uppercase dark:text-white">
                                            Username
                                        </th>
                                        <th scope="col"
                                            class="p-4 text-xs font-large text-left text-white uppercase dark:text-white">
                                            Email
                                        </th>
                                        <th scope="col"
                                            class="p-4 text-xs font-large text-left text-white uppercase dark:text-white">
                                            Password
                                        </th>
                                        <th scope="col"
                                            class="p-4 text-xs font-large text-left text-white uppercase dark:text-white">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @php
                                        $counter = 1;
                                    @endphp
                                    @foreach ($instruktur as $ins)
                                        <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                            <td class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white searchable">
                                                {{ $ins->instruktur_id }}</td>
                                            <td class="flex items-center p-4 mr-12 space-x-6 whitespace-nowrap searchable">
                                                <div class="text-sm font-small text-gray-500 dark:text-gray-400">
                                                    <a class="text-sm font-small text-blue-500 dark:text-blue-500" href="{{route('admin.viewDetailInstruktur', $ins->instruktur_id)}}">{{ $ins->instruktur_nama }}</a>
                                                </div>
                                            </td>
                                            <td
                                                class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white searchable">
                                                {{ $ins->username }}</td>
                                            <td
                                                class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white searchable">
                                                {{ $ins->email }}</td>
                                            <td
                                                class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white">
                                                {{ $ins->password_awal }}</td>
                                            <td class="p-4 space-x-2 whitespace-nowrap ">
                                                @if($ins && $ins->instruktur_id)
                                                <a href="{{ route('admin.editInstruktur',[$ins->instruktur_id]) }}" class="inline-flex items-center px-2 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </a>
                                                @endif
                                                <button type="button" data-modal-target="delete-instruktur-modal-{{ $ins->instruktur_id }}" data-modal-toggle="delete-instruktur-modal-{{ $ins->instruktur_id }}"
                                                    class="inline-flex items-center px-2 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900 ml-3">
                                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </button>

                                                <div id="delete-instruktur-modal-{{ $ins->instruktur_id }}" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                    <div class="relative p-4 w-full max-w-md max-h-full">
                                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="delete-instruktur-modal-{{ $ins->instruktur_id }}">
                                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                                </svg>
                                                                <span class="sr-only">Close modal</span>
                                                            </button>
                                                            <div class="p-4 md:p-5 text-center">
                                                                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                                </svg>
                                                                <h3 class="mb-5 text-m font-normal text-gray-500 dark:text-gray-400">Apakah Anda yakin ingin menghapus instruktur ini?</h3>
                                                                <div class="flex space-x-2 justify-center">
                                                                    <form id="deleteForm" method="POST" action="{{route('admin.deleteInstruktur', $ins->instruktur_id)}}">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                        <button data-modal-hide="delete-instruktur-modal-{{ $ins->instruktur_id }}" type="submit" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                                            Ya
                                                                        </button>
                                                                    </form>
                                                                    <button data-modal-hide="delete-instruktur-modal-{{ $ins->instruktur_id }}" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">
                                                                        Tidak
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- <div id="delete-instruktur-modal-{{ $ins->instruktur_id }}" tabindex="-1" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
                                                    <div class="relative p-4 w-full max-w-md max-h-full">
                                                        <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                                            <button type="button" class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="delete-instruktur-modal-{{ $ins->instruktur_id }}">
                                                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                                                                </svg>
                                                                <span class="sr-only">Close modal</span>
                                                            </button>
                                                            <div class="p-4 md:p-5 text-center">
                                                                <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                                                                </svg>
                                                                <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400 -mx-12">Apakah Anda yakin ingin menghapus instruktur ini?</h3>
                                                                <button data-modal-hide="delete-instruktur-modal-{{ $ins->instruktur_id }}" type="button" class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                                                                    Ya
                                                                </button>
                                                                <button data-modal-hide="delete-instruktur-modal-{{ $ins->instruktur_id }}" type="button" class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">Batal</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> -->
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="fixed left-0 right-0 z-50 items-center justify-center hidden top-8 md:inset-0 sm:h-full"
        id="email-instruktur-modal">
        <div class="relative w-full h-full max-w-2xl px-4 md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800 overflow">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                    <h3 class="text-xl font-semibold dark:text-white">
                        Kirim Email instruktur
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                        data-modal-toggle="email-instruktur-modal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6 overflow-y-auto">
                    <form id="emailForm" method="POST" action="{{route('admin.sendEmailInstruktur')}}" enctype="multipart/form-data">
                        @csrf
                        <div class="col-span-6 sm:col-span-6">
                            <label for="subjek"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Subjek</label>
                            <input type="text" name="subjek" placeholder="Tulis Subjek" id="subjek"
                                class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                required>
                            @error('subjek')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col-span-6 justify-between items-center">
                        <div class="items-center">
                            <label for="deliver-option" class="text-sm font-medium text-gray-900 dark:text-white">Kepada</label>
                            <select name="deliver_option" id="deliver-option" class="block w-full mt-2 mb-2 block w-32 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">
                                <option value="all">Semua</option>
                                <option value="range">Rentang</option>
                            </select>

                            <!-- Rentang id -->
                            <div id="deliver-range" class="hidden">
                                <div class="flex-col items-center mt-2">
                                    <label for="start_user_id" class="text-sm font-medium text-gray-900 dark:text-white">Pilih Rentang User ID:</label>
                                    <select name="start_user_id" id="start_user_id" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="" class="text-sm font-medium" selected disabled>Pilih User ID</option>
                                        @if ($instruktur2 && !$instruktur2->isEmpty())
                                            @foreach ($instruktur2 as $in)
                                                <option value="{{ $in->users_id}}">{{ $in->users_id }} - {{$in->nama}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="flex-col items-center mt-2 mb-4">
                                    <label for="end_user_id" class="text-sm font-medium text-gray-900 dark:text-white">Akhir User ID:</label>
                                    <select name="end_user_id" id="end_user_id" class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option value="" class="text-sm font-medium" selected disabled>Pilih User ID</option>
                                        @if ($instruktur2 && !$instruktur2->isEmpty())
                                            @foreach ($instruktur2 as $in)
                                                <option value="{{ $in->users_id }}">{{ $in->users_id}} - {{$in->nama}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-span-6 sm:col-span-6">
                            <label for="kode"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Pelatihan</label>
                            <select name="kode" id="kode" class="mb-2 shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                    <option value="" class="text-sm font-medium" selected disabled>Pilih Pelatihan</option>
                                    @foreach ($pelatihan as $data)
                                        <option value="{{ $data->kode }}">{{ $data->nama }}</option>
                                    @endforeach
                                </select>
                            @error('kode')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class=" mt-2 justify-between items-center space-x-4">
                            <button type="submit" class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                Send
                            </button>
                            <button type="button" class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800" data-modal-toggle="email-instruktur-modal">
                                Batal
                            </button>
                        </div>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('deliver-option').addEventListener('change', function() {
            var exportOption = this.value;
            if (exportOption === 'range') {
                document.getElementById('deliver-range').classList.remove('hidden');
            } else {
                document.getElementById('deliver-range').classList.add('hidden');
            }
        });
    </script>
        
    <script>
        document.getElementById('export-option').addEventListener('change', function() {
            var exportOption = this.value;
            if (exportOption === 'range') {
                document.getElementById('export-range').classList.remove('hidden');
            } else {
                document.getElementById('export-range').classList.add('hidden');
            }
        });
    </script>
    
    @foreach ($instruktur as $ins)
    <div class="fixed left-0 right-0 z-50 items-center justify-center hidden top-8 md:inset-0 sm:h-50"
        id="delete-instruktur-modal-{{ $ins->instruktur_id }}">
        <div class="relative w-50 h-50 max-w-2xl px-4 md:h-50">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800 overflow">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                    <h3 class="text-xl font-semibold dark:text-white">
                        Hapus instruktur
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                        data-modal-toggle="delete-instruktur-modal-{{ $ins->instruktur_id }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6 overflow-y-auto">
                    <label class="block mb-2 text-sm font-medium text-gray-900 w-full dark:text-white p-2" for="delete">Apakah anda yakin ingin menghapus instruktur {{$ins->nama}}?</label>
                    <div class="col-span-full flex justify-between">
                        <form id="deleteForm" method="POST" action="{{route('admin.deleteInstruktur', $ins->instruktur_id)}}">
                            @csrf
                            @method('DELETE')
                            <div class="flex">
                                <button type="submit"
                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 mr-4"
                                    data-modal-toggle="delete-instruktur-modal-{{ $ins->instruktur_id }}">
                                    Hapus
                                </button>
                                <button type="button"
                                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                    data-modal-toggle="delete-instruktur-modal-{{ $ins->instruktur_id }}">
                                    Batal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach

    <script>
        document.addEventListener('DOMContentLoaded', function() {
        var table = document.getElementById('myTable');
        var rows = table.rows;
        var isSortAscendingId = true;
        var isSortAscendingName = true;

        document.getElementById('sortIcon').addEventListener('click', function() {
            sortById(); // sort by ID
        });

        document.getElementById('sortButton').addEventListener('click', function() {
            sortByName(); // sort by name
        });

        function sortById() {
            var sortedRows = Array.from(rows).slice(1).sort(function(a, b) {
                var cellA = Number(a.cells[0].innerText);
                var cellB = Number(b.cells[0].innerText);

                if (isNaN(cellA) || isNaN(cellB)) {
                    // If either value is not a number, return 0 (no change)
                    return 0;
                }

                if (isSortAscendingId) {
                    return cellA - cellB;
                } else {
                    return cellB - cellA;
                }
            });

            for (var i = 0; i < sortedRows.length; i++) {
                table.appendChild(sortedRows[i]);
            }

            isSortAscendingId = !isSortAscendingId;
        }

        function sortByName() {
            var sortedRows = Array.from(rows).slice(1).sort(function(a, b) {
                var cellA = a.cells[1].innerText;
                var cellB = b.cells[1].innerText;

                if (isSortAscendingName) {
                    return cellA.localeCompare(cellB);
                } else {
                    return cellB.localeCompare(cellA);
                }
            });

            for (var i = 0; i < sortedRows.length; i++) {
                table.appendChild(sortedRows[i]);
            }

            isSortAscendingName = !isSortAscendingName;
        }
    });
    </script>
@endsection