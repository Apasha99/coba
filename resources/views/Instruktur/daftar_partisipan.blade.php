@extends('instruktur.layout.layout_tabs')
<link
	href="https://fonts.googleapis.com/css?family=Material+Icons|Material+Icons+Outlined|Material+Icons+Two+Tone|Material+Icons+Round|Material+Icons+Sharp"
	rel="stylesheet">
@section('tabs')
<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px">
        <li class="me-2">
            <a href="{{ route('instruktur.viewDetailPelatihan', $pelatihan->kode) }}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Pelatihan</a>
        </li>
        <li class="me-2">
            <a href="{{ route('instruktur.viewDaftarPartisipan', $pelatihan->kode) }}" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">Partisipan</a>
        </li>
        <li class="me-2">
            <a href="{{ route('test.rekap', ['plt_kode' => $pelatihan->kode]) }}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Rekap Test</a>
        </li>
    </ul>
</div>
@section('content')
    <div class="col-span-full xl:mb-2">
    <nav class="flex mb-4" aria-label="Breadcrumb">
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
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Daftar Partisipan</span>
                    </div>
                </li>
            </ol>
        </nav>
    </div>
    <div class="flex flex-col xl:mb-2">
        <h1 class="mb-3 text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Daftar Partisipan</h1>
        <!-- <div class="relative w-64">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
            </svg>
            </div>
            <input type="text" name="search" id="searchInput" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari partisipan" required>
        </div> -->
    </div>
    <div
        class="bg-white block sm:flex items-center justify-between border-b border-gray-200 mb-4 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg rounded-lg">
                            <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600" id="myTable">
                                <thead class="bg-indigo-600 dark:bg-indigo-700">
                                    <tr>
                                        <th scope="col" class="p-4 text-xs font-medium text-left text-white uppercase dark:text-white">
                                            No
                                        </th>
                                        <th scope="col"
                                            class="p-4 text-xs font-medium text-left text-white uppercase dark:text-white">
                                            Nama
                                        </th>
                                        <th scope="col"
                                            class="p-4 text-xs font-medium text-left text-white uppercase dark:text-white">
                                            Role
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-zinc-100 divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @php
                                    $counter = 1;
                                @endphp
                                @foreach ($instrukturTerdaftar as $instruktur)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                                            <div class="text-base font-small text-gray-900 dark:text-white">{{ $counter++ }}</div>
                                        </td>
                                        <td class="flex items-center p-4 mr-12 space-x-6 whitespace-nowrap">
                                            <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                                <div class="text-base font-small text-gray-900 dark:text-white">{{ $instruktur->instruktur->nama }}</div>
                                            </div>
                                        </td>
                                        <td
                                            class="p-4 text-base font-small text-gray-900 whitespace-nowrap dark:text-white">
                                            Instruktur
                                        </td>
                                    </tr>
                                @endforeach    
                                @foreach ($pesertaTerdaftar as $peserta)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="p-4 text-sm font-normal text-gray-500 dark:text-gray-400">
                                            <div class="text-base font-small text-gray-900 dark:text-white">{{ $counter++ }}</div>
                                        </td>
                                        <td class="flex items-center p-4 mr-12 space-x-6 whitespace-nowrap">
                                            <div class="text-sm font-normal text-gray-500 dark:text-gray-400">
                                                <div class="text-base font-small text-gray-900 dark:text-white">{{ $peserta->peserta->nama }}</div>
                                            </div>
                                        </td>
                                        <td
                                            class="p-4 text-base font-small text-gray-900 whitespace-nowrap dark:text-white">
                                            Peserta
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
@endsection
@endsection






