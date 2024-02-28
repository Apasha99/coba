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
                        <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Admin</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Semua Admin</h1>
    </div>
    <div class="sm:flex mb-4">
        <div class="relative mb-4">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
            </svg>
            </div>
            <input type="text" name="search" id="searchInput" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari admin" required>
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
            <a type="button" href ="{{route('admin.createAdmin')}}"
                class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd"></path>
                </svg>
                Tambah Admin
            </a>
            <a href="{{route('admin.downloadAdmin')}}"
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
    <div
        class="bg-white block sm:flex items-center justify-between border-b border-gray-200 lg:mt-1.5 dark:bg-gray-800 dark:border-gray-700">
        <div class="w-full mb-1">
            <div class="flex flex-col">
                <div class="overflow-x-auto">
                    <div class="inline-block min-w-full align-middle">
                        <div class="relative overflow-x-auto shadow-md sm:rounded-lg rounded-lg">
                            <div class="table-container">
                                <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600" id="myTable">
                                    <thead class="bg-indigo-600 dark:bg-indigo-700">
                                        <tr>
                                            <th scope="col" class="p-4 text-xs font-large items-center justify-start text-white uppercase dark:text-white">
                                                <span style="float: left;">ID</span>
                                                <span id="sortIcon" style="float: left;">
                                                    <svg class="ml-2 w-4 h-4 text-white dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                                    </svg>
                                                </span>
                                                <div style="clear: both;"></div>
                                            </th>

                                            <th scope="col" class="p-4 text-xs font-large text-left text-white uppercase dark:text-white">
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
                                            @if (auth()->check() && auth()->user()->id == 1)
                                                <th scope="col" class="p-4 text-xs font-large text-left text-white uppercase dark:text-white">
                                                    Actions
                                                </th>
                                            @endif

                                        </tr>
                                    </thead>
                                    <tbody class="bg-zinc-100 divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700 overflow-y-auto max-h-80">
                                        @foreach ($admin2 as $plt)
                                            <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                                <td
                                                    class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white searchable">
                                                    {{ $plt->admin_id }}</td>
                                                <td class="flex items-center p-4 mr-12 space-x-6 whitespace-nowrap searchable">
                                                    @if($plt->foto)
                                                        <img class="w-10 h-10 rounded-full" src="{{ asset('storage/' . $plt->foto) }}" alt="{{ $plt->nama }}">
                                                    @else
                                                        <div class="w-10 h-10 bg-gray-300 rounded-full"></div> <!-- Placeholder jika tidak ada gambar -->
                                                    @endif
                                                    <div class="text-sm font-small text-gray-500 dark:text-gray-400">
                                                        <a class="text-sm font-small text-blue-500 dark:text-blue-500" href="{{route('admin.viewDetailAdmin', $plt->admin_id)}}">{{ Illuminate\Support\Str::limit($plt->nama, 30, '...') }}</a>
                                                    </div>
                                                </td>
                                                <td
                                                    class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white searchable">
                                                    {{ $plt->username }}</td>
                                                <td
                                                    class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white searchable">
                                                    {{ $plt->email }}</td>
                                                <td
                                                    class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white">
                                                    {{ $plt->password_awal }}</td>
                                                @if (auth()->check() && auth()->user()->id == 1)
                                                <td class="p-4 space-x-2 whitespace-nowrap ">
                                                    @if($plt && $plt->admin_id)
                                                    <a href="{{route('admin.editAdmin', $plt->admin_id)}}" class="inline-flex items-center px-2 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                                            <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </a>
                                                    @endif
                                                    <button type="button" data-modal-toggle="delete-admin-modal-{{ $plt->admin_id }}"
                                                        class="inline-flex items-center px-2 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900 ml-3">
                                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </button>
                                                </td>
                                                @endif

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
    </div>
    @foreach ($admin2 as $plt)
    <div class="fixed left-0 right-0 z-50 items-center justify-center hidden top-8 md:inset-0 sm:h-50"
        id="delete-admin-modal-{{ $plt->admin_id }}">
        <div class="relative w-50 h-50 max-w-2xl px-4 md:h-50">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800 overflow">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                    <h3 class="text-xl font-semibold dark:text-white">
                        Hapus Admin
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                        data-modal-toggle="delete-admin-modal-{{ $plt->admin_id }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6 overflow-y-auto">
                    <label class="block mb-2 text-sm font-medium text-gray-900 w-full dark:text-white p-2" for="delete">Apakah anda yakin ingin menghapus admin {{$plt->nama}}?</label>
                    <div class="col-span-full flex justify-between">
                        <form id="deleteForm" method="POST" action="{{route('admin.deleteAdmin', $plt->admin_id)}}">
                            @csrf
                            @method('DELETE')
                            <div class="flex">
                                <button type="submit"
                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 mr-4"
                                    data-modal-toggle="delete-admin-modal-{{ $plt->admin_id }}">
                                    Hapus
                                </button>
                                <button type="button"
                                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                    data-modal-toggle="delete-admin-modal-{{ $plt->admin_id }}">
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
        function navigateAction(select) {
            var selectedOption = select.options[select.selectedIndex];
            var url = selectedOption.getAttribute('data-url');
            
            if (url) {
                window.location.href = url;
            }
        }

        function handleActionChange(select) {
            // Periksa apakah nilai yang dipilih adalah "delete-option"
            if (select.value === 'delete-option') {
                // Dapatkan ID modal dari data-modal-id
                var modalId = select.options[select.selectedIndex].getAttribute('data-modal-id');

                // Tampilkan modal penghapusan berdasarkan ID modal
                document.getElementById(modalId).classList.remove('hidden');
            }
        }

    </script>
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


