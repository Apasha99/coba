@extends('instruktur.layout.layout')


@section('content')
    <div class="mb-4 col-span-full xl:mb-2">
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
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Daftar Pelatihan</span>
                    </div>
                </li>
            </ol>
        </nav>
        <div class="text-center">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Daftar Pelatihan</h1>
        </div>
    </div>

    <div >
        <div class="col-span-full xl:col-auto">
            <div class="flex items-center sm:space-x-3 justify-between">
                <div class="items-center hidden mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700 ">
                    <input type="text" id="searchInput" placeholder="Search" class="mt-2 mr-3 mb-4 p-2 border border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                </div>
                <script>
                    // Ambil input elemen pencarian
                    const searchInput = document.getElementById('searchInput');

                    // Tambahkan event listener untuk input pencarian
                    searchInput.addEventListener('input', function(event) {
                        const searchText = event.target.value.toLowerCase(); // Ambil teks pencarian dan ubah menjadi lowercase
                        const rows = document.querySelectorAll('.pelatihan-row'); // Ambil semua baris yang berisi data pelatihan

                        // Iterasi melalui setiap baris
                        rows.forEach(row => {
                            let matchFound = false; // Inisialisasi variabel untuk menandai apakah pencocokan ditemukan dalam baris tertentu

                            // Ambil seluruh elemen dalam baris yang ingin dicocokkan (nama, kode, status)
                            const elementsToSearch = row.querySelectorAll('.searchable');

                            // Iterasi melalui setiap elemen yang ingin dicocokkan
                            elementsToSearch.forEach(element => {
                                const text = element.textContent.toLowerCase(); // Ambil teks dari elemen tersebut

                                // Jika teks pencarian cocok dengan teks pada elemen, tandai pencocokan ditemukan
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
            </div>
        </div>

        <div class="flex flex-wrap">
        @foreach($pelatihan as $plt)
        <div class="relative lock mt-2 w-70 h-50 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 pelatihan-row">
                <a href="{{ route('instruktur.viewDetailPelatihan', $plt->kode) }}" >
                    <img src="{{ $plt->getPosterURL() }}" alt="poster pelatihan" class="sm:w-60 md:w-80 mb-2 h-40 object-cover rounded-t-lg " />
                    <div class="items-center p-2 sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4">
                    <div>
                        <h3 class="mb-1 text-l font-bold text-gray-900 dark:text-white">{{ $plt->nama }}</h3>
                        <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                            <p><span class="bg-blue-100 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">{{ $plt->kode }}</span>
                            @if ($plt->status == 'On going')
                            <span class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $plt->status }}</span></p> 
                            @elseif ($plt->status == 'Completed')
                            <span class="bg-red-100 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">{{ $plt->status }}</span>
                            @elseif ($plt->status == 'Not started yet')
                            <span class="bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">{{ $plt->status }}</span>
                            @endif
                        </div>
                    </div>
                </div>
                </a>
            </div>
        @endforeach
    </div>
    </div>
    
@endsection


