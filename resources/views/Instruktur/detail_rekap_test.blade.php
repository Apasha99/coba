@extends('instruktur.layout.layout_tabs')
@section('tabs')
<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px">
        <li class="me-2">
            <a href="{{ route('instruktur.viewDetailPelatihan', $pelatihan->kode) }}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Pelatihan</a>
        </li>
        <li class="me-2">
            <a href="{{ route('instruktur.viewDaftarPartisipan', $pelatihan->kode) }}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300" aria-current="page">Partisipan</a>
        </li>
        <li class="me-2">
            <a href="{{ route('test.rekap', ['plt_kode' => $pelatihan->kode]) }}" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">Rekap Test</a>
        </li>
    </ul>
</div>
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
                <li class="inline-flex items-center">
                    <a href="{{route('test.rekap', ['plt_kode'=>$pelatihan->kode])}}"
                        class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Rekap Test
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
                        <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Detail Rekap Test</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Test {{$test2->nama}}</h1>
    </div>
    <div class="sm:flex mb-4">
        <div class="relative mb-4">
            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
            </svg>
            </div>
            <input type="text" name="search" id="searchInput" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Cari peserta" required>
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
            <a href="{{route('rekap.downloadDetail',[$pelatihan->kode,$test2->id])}}"
                class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                <svg class="w-5 h-5  mr-2 -ml-1 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                    <path fill-rule="evenodd" d="M13 11.1V4a1 1 0 1 0-2 0v7.1L8.8 8.4a1 1 0 1 0-1.6 1.2l4 5a1 1 0 0 0 1.6 0l4-5a1 1 0 1 0-1.6-1.2L13 11Z" clip-rule="evenodd"/>
                    <path fill-rule="evenodd" d="M9.7 15.9 7.4 13H5a2 2 0 0 0-2 2v4c0 1.1.9 2 2 2h14a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-2.4l-2.3 2.9a3 3 0 0 1-4.6 0Zm7.3.1a1 1 0 1 0 0 2 1 1 0 1 0 0-2Z" clip-rule="evenodd"/>
                </svg>
                Download Rekap
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
                    <div class="inline-block min-w-full  align-middle">
                        <div class="overflow-hidden shadow sm:rounded-lg rounded-lg">
                            <table class="min-w-full divide-y-100 divide-gray-200 table-fixed dark:divide-gray-800" id="myTable">
                                <thead class="bg-purple-600 dark:bg-purple-500" style="width: 100%; margin-left: 0;">
                                    <tr>
                                        <th scope="col" class="p-4 text-xs font-large text-left text-white uppercase dark:text-white">
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
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-large text-left text-white uppercase dark:text-white" data-column="kkm" data-type="number">
                                            <span class="flex items-center">
                                                KKM
                                            </span>
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-large text-left text-white uppercase dark:text-white" data-column="nilai" data-type="number">
                                            <span class="flex items-center">
                                                Nilai
                                            </span>
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-large text-left text-white uppercase dark:text-white" data-column="nilai" data-type="number">
                                            <span class="flex items-center">
                                                Jumlah Attempt
                                            </span>
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-large text-left text-white uppercase dark:text-white" data-column="status" data-type="string">
                                            <span class="flex items-center">
                                                Status
                                            </span>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    
                                    @foreach ($highestScores as $score)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white searchable" >{{ $score->user_id }} </td>
                                        <td class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white searchable" >{{ $score->nama }}</td>
                                        <td class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white searchable" >{{ $test2->kkm }}</td>
                                        <td class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white searchable" >{{ $score->max_totalnilai }}</td>
                                        <td class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white searchable" >{{ $score->jumlah_attempt }}</td>
                                        <td class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white" >
                                            @if ($score->max_totalnilai >= $test2->kkm)
                                            <div class="mt-2 p-2 bg-green-200 rounded-lg">
                                                <p class="text-center text-sm font-small text-green-800">Passed</p>
                                            </div>
                                            @else
                                            <div class="mt-2 p-2 bg-red-200 rounded-lg">
                                                <p class="text-center text-sm font-small text-red-800">Failed</p>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td colspan="5" class="text-center p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">Total Nilai</td>
                                        <td class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white">{{ $hitungnilai }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td colspan="5" class="text-center p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">Total Peserta</td>
                                        <td class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white">{{ $totalpeserta }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td colspan="5" class="text-center p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">Peserta yang Mengerjakan Test</td>
                                        <td class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white">{{ $hitungpeserta }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td colspan="5" class="text-center p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">Rata-rata</td>
                                        <td class="p-4 text-sm font-small text-gray-900 whitespace-nowrap dark:text-white">
                                            @if ($hitungpeserta > 0)
                                                {{ $hitungnilai / $hitungpeserta }}
                                            @else
                                                0
                                            @endif
                                        </td>
                                    </tr>

                                </tbody>
                            </table>
                            <title>Grafik Jumlah Peserta per Rentang Nilai</title>
                            <canvas id="grafikPeserta" style="width: 0px; height: 0px;"></canvas>
                            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                            <script>
                                // Data jumlah peserta per rentang nilai
                                const jumlahPesertaPerRentang = <?php echo json_encode($jumlahPesertaPerRentang); ?>;

                                // Membuat array rentang nilai dan jumlah peserta
                                const rentangNilai = Object.keys(jumlahPesertaPerRentang);
                                const jumlahPeserta = Object.values(jumlahPesertaPerRentang);

                                // Membuat grafik garis
                                const ctx = document.getElementById('grafikPeserta').getContext('2d');
                                const grafik = new Chart(ctx, {
                                    type: 'line',
                                    data: {
                                        labels: rentangNilai,
                                        datasets: [{
                                            label: 'Jumlah Peserta',
                                            data: jumlahPeserta,
                                            fill: false,
                                            borderColor: 'rgb(75, 192, 192)',
                                            tension: 0.1
                                        }]
                                    },
                                    options: {
                                        scales: {
                                            y: {
                                                beginAtZero: true,
                                                title: {
                                                    display: true,
                                                    text: 'Jumlah Peserta'
                                                }
                                            },
                                            x: {
                                                title: {
                                                    display: true,
                                                    text: 'Rentang Nilai'
                                                }
                                            }
                                        }
                                    }
                                });
                            </script>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
@endsection

