@extends('peserta.layout.layout')


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
                        <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">History Pelatihan</span>
                    </div>
                </li>
            </ol>
        </nav>
        <div class="text-left">
            <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">History Pelatihan</h1>
        </div>
    </div>

    <div >
        <div class="col-span-full xl:col-auto">
            <div class="flex items-center sm:space-x-3 justify-between">
                <div class="items-center hidden mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700 ">
                    
                <input type="text" id="searchInput" placeholder="Search for pelatihan" class="mt-2 mr-3 mb-4 p-2 border border-gray-200 rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                </div>
                <button id="toggleSidebarMobileSearch" type="button"
                        class="p-2 text-gray-500 rounded-lg hover:text-gray-900 hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white">
                        <span class="sr-only">Search</span>

                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
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

        <div class="grid grid-cols-3 gap-4">
            @foreach ($pelatihan as $plt)
            <div class="relative lock mt-2 w-70 h-50 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 pelatihan-row">
                <a href="{{ route('peserta.viewDetailPelatihan', $plt->kode) }}" >
                    <img src="{{ $plt->getPosterURL() }}" alt="poster pelatihan" class="sm:w-60 md:w-80 mb-2 h-40 object-cover rounded-t-lg " />
                    <div class="items-center sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4">
                        <div class="ml-2">
                            <h3 class="mb-1 text-l font-bold text-gray-900 dark:text-white searchable">{{ Illuminate\Support\Str::limit($plt->nama, 50, '...') }}</h3>
                            <div class="mb-4 text-sm text-gray-500 dark:text-gray-400 searchable">
                                <p>
                                    <span class="searchable bg-blue-200 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-200">{{ $plt->kode }}</span>
                                    @if ($plt->status == 'On going')
                                    <span class="searchable bg-green-200 text-green-900 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-100">Aktif</span> 
                                    @elseif ($plt->status == 'Completed')
                                    <span class="searchable dark:bg-red-900 bg-red-300 text-red-900 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:text-red-200">Selesai</span>
                                    @elseif ($plt->status == 'Not started yet')
                                    <span class="searchable bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Belum mulai</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
                <div class="fixed left-0 right-0 z-50 items-center justify-center hidden top-8 md:inset-0 sm:h-50"
                id="delete-pelatihan-modal-{{ $plt->kode }}">
                <div class="relative w-50 h-50 max-w-2xl px-4 md:h-50">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-800 overflow">
                        <!-- Modal header -->
                        <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                            <h3 class="text-xl font-semibold dark:text-white">
                                Hapus Pelatihan
                            </h3>
                            <button type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                                data-modal-toggle="delete-pelatihan-modal-{{ $plt->kode }}">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd"
                                        d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                        clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-6 overflow-y-auto">
                            <label class="block mb-2 text-sm font-medium text-gray-900 w-full dark:text-white p-2" for="delete">Apakah anda yakin ingin menghapus pelatihan {{$plt->nama}}?</label>
                            <div class="col-span-full flex justify-between">
                                <form id="deleteForm" method="POST" action="{{ route('admin.deletePelatihan', $plt->kode) }}">
                                    @csrf
                                    @method('DELETE')
                                    <div class="flex">
                                        <button type="submit"
                                            class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 mr-4"
                                            data-modal-toggle="delete-pelatihan-modal-{{ $plt->kode }}">
                                            Hapus
                                        </button>
                                        <button type="button"
                                            class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                            data-modal-toggle="delete-pelatihan-modal-{{ $plt->kode }}">
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
        </div>
    </div>
    
@endsection


