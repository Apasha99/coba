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
                <li class="inline-flex items-center">
                    <a href="{{route('admin.viewDaftarPelatihan')}}"
                        class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Daftar Pelatihan
                    </a>
                </li>
                <li class="inline-flex items-center">
                    <a href="{{route('admin.viewDetailPelatihan', ['plt_kode'=>$pelatihan->kode])}}"
                        class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Detail Pelatihan
                    </a>
                </li>
                <li class="inline-flex items-center">
                    <a href="{{route('admin.rekapTest', ['plt_kode'=>$pelatihan->kode])}}"
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
        <div class="items-center hidden mb-3 sm:flex sm:divide-x sm:divide-gray-100 sm:mb-0 dark:divide-gray-700">
            <form class="lg:pr-3" action="{{route('admin.searchDetailTest',['plt_kode'=>$test2->plt_kode,'test_id'=>$test2->id])}}" method="GET">
                <label for="search" class="sr-only">Search</label>
                <div class="relative mt-1 lg:w-64 xl:w-96">
                    <input type="text" name="search" id="search"
                        class="bg-gray-100 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                        placeholder="Search for peserta">
                    <button type="submit" class="absolute inset-y-0 right-0 px-3 py-1 bg-gray-200 rounded-r-lg">
                        Search
                    </button>
                </div>
            </form>

            
        </div>
        <div class="flex items-center ml-auto space-x-2 sm:space-x-3">
            <a href="{{route('admin.downloadDetailRekap',[$pelatihan->kode,$test2->id])}}"
                class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-gray-900 bg-white border border-gray-300 rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700 dark:focus:ring-gray-700">
                <i class="fa-solid fa-file-arrow-up fa-lg mr-3"></i>
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
                        <div class="overflow-hidden shadow">
                            <table class="min-w-full divide-y-100 divide-gray-200 table-fixed dark:divide-gray-800">
                                <thead class="bg-gray-100 dark:bg-gray-700" style="width: 100%; margin-left: 0;">
                                    <tr>
                                        <th scope="col" class="p-4 text-xs font-large text-left text-gray-500 uppercase dark:text-gray-400" data-column="id" data-type="number">
                                            <span class="flex items-center">
                                                ID
                                                <svg id="sort-icon-user-id" class="ml-2 w-5 h-5 text-gray-500 dark:text-white cursor-pointer" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                                </svg>
                                            </span>
                                        </th>

                                        <th scope="col" class="p-4 text-xs font-large text-left text-gray-500 uppercase dark:text-gray-400" data-column="nama" data-type="string">
                                            <span class="flex items-center">
                                                Nama Peserta
                                                <svg id="sort-icon-nama" class="ml-2 w-5 h-5 text-gray-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                                </svg>
                                            </span>
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-large text-left text-gray-500 uppercase dark:text-gray-400" data-column="kkm" data-type="number">
                                            <span class="flex items-center">
                                                KKM
                                                <svg id="sort-icon-kkm" class="ml-2 w-5 h-5 text-gray-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                                </svg>
                                            </span>
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-large text-left text-gray-500 uppercase dark:text-gray-400" data-column="nilai" data-type="number">
                                            <span class="flex items-center">
                                                Nilai
                                                <svg id="sort-icon-nilai" class="ml-2 w-5 h-5 text-gray-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                                </svg>
                                            </span>
                                        </th>
                                        <th scope="col" class="p-4 text-xs font-large text-left text-gray-500 uppercase dark:text-gray-400" data-column="status" data-type="string">
                                            <span class="flex items-center">
                                                Status
                                                <svg id="sort-icon-status" class="ml-2 w-5 h-5 text-gray-500 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                                                </svg>
                                            </span>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @php
                                        // Mengonversi koleksi ke dalam array
                                        $nilaiPesertaArray = $nilaiPeserta->toArray();

                                        // Mengurutkan array
                                        usort($nilaiPesertaArray, function($a, $b) {
                                            // Bandingkan user_id terlebih dahulu
                                            if ($a['user_id'] != $b['user_id']) {
                                                return $a['user_id'] - $b['user_id'];
                                            }

                                            // Jika user_id sama, bandingkan kolom lain
                                            // Misalnya, kolom nama
                                            return strcmp($a['nama'], $b['nama']);
                                        });
                                    @endphp
                                    @foreach ($nilaiPeserta as $score)
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white" >{{ $score->user_id }} </td>
                                        <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white" >{{ $score->nama }}</td>
                                        <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white" >{{ $test2->kkm }}</td>
                                        <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white" >{{ $score->total_nilai }}</td>
                                        <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white" >
                                            @if ($hitungnilai >= $test2->kkm)
                                            <div class="mt-2 p-2 bg-green-200 rounded-lg">
                                                <p class="text-center text-sm font-semibold text-green-800">Passed</p>
                                            </div>
                                            @else
                                            <div class="mt-2 p-2 bg-red-200 rounded-lg">
                                                <p class="text-center text-sm font-semibold text-red-800">Failed</p>
                                            </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td colspan="4" class="text-center p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">Total Nilai</td>
                                        <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $hitungnilai }}</td>
                                    </tr>
                                    <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                                        <td colspan="4" class="text-center p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">Rata-rata</td>
                                        <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $hitungnilai/$hitungpeserta }}</td>
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
@endsection


