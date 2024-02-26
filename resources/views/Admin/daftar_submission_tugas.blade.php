@extends('admin.layout.layout_tabs')
<link
	href="https://fonts.googleapis.com/css?family=nilaial+Icons|nilaial+Icons+Outlined|nilaial+Icons+Two+Tone|nilaial+Icons+Round|nilaial+Icons+Sharp"
	rel="stylesheet">
@section('tabs')
<div class="text-sm font-medium text-center text-gray-500 border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px">
        <li class="me-2">
            <a href="{{ route('admin.viewDetailPelatihan', $pelatihan->kode) }}" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">Pelatihan</a>
        </li>
        <li class="me-2">
            <a href="{{ route('admin.viewDaftarPartisipan', $pelatihan->kode) }}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Partisipan</a>
        </li>
        <li class="me-2">
            <a href="{{ route('test.rekap', ['plt_kode' => $pelatihan->kode]) }}" class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300">Rekap Test</a>
        </li>
    </ul>
</div>
@section('content')
    <div class="mb-2 col-span-full xl:mb-2">
        <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="admin/dashboard"
                        class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                        <svg class="w-5 h-5 mr-2.5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z">
                            </path>
                        </svg>
                        Home
                    </a>
                </li>
                <li class="flex items-center">
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
                <li class="flex items-center">
                    <a href="{{route('admin.viewDetailPelatihan', $pelatihan->kode)}}"
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
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Daftar Submission Tugas</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Daftar Submission Tugas</h1>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <p class="text-sm font-normal text-gray-500 truncate dark:text-gray-400">
            
        </p>
        <span class="bg-green-100 text-green-800 text-sm font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $submissions->count() }} jawaban</span>
    </div>

    <div class="relative overflow-x-auto shadow-md sm:rounded-lg rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600" id="myTable">
        <thead class="text-xs text-white uppercase bg-indigo-600 dark:bg-indigo-700 dark:text-white">
            <tr>
                <th scope="col" class="px-6 py-3">
                <span style="float: left;">Nama</span>
                    <span id="sortNama" style="float: left;">
                        <svg class="ml-2 w-4 h-4 text-gray dark:text-gray" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                    <div style="clear: both;"></div>
                </th>
                <th scope="col" class="px-6 py-3">
                    Waktu Submit
                </th>
                <th scope="col" class="px-6 py-3">
                    <span style="float: left;">Nilai</span>
                    <span id="sortNilai" style="float: left;">
                        <svg class="ml-2 w-4 h-4 text-gray dark:text-gray" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4"/>
                        </svg>
                    </span>
                    <div style="clear: both;"></div>
                </th>
                <th scope="col" class="px-6 py-3">
                    File Jawaban
                </th>
                <th scope="col" class="px-6 py-3">
                    Aksi
                </th>
            </tr>
        </thead>
        <tbody>
            @foreach ($submissions as $submission)
            <tr class="bg-zinc-100 border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 text-sm font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    {{ $submission->peserta->nama }}
                </th>
                <td class="px-6 py-4 text-sm dark:text-white">
                    {{ $submission->submitted_at }}
                    @if ($submission->submitted_at > $tugas->end_date)
                        <div class="mt-2">
                            <span class="bg-red-200 text-red-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-red-900 dark:text-red-300">Terlambat</span>
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4 text-sm dark:text-white">
                    @if($submission->grading_status == 'not graded')
                        Not graded yet
                    @else
                        {{ $submission->nilai }}
                    @endif
                </td>
                <td class="px-6 py-4 text-sm ">
                    @foreach ($submission->submission_file as $file)
                        <a href="{{ asset('storage/' . $file->path_file) }}" class="text-blue-600 dark:text-blue-700 hover:underline" target="_blank">{{ $file->nama_file }}</a><br>
                    @endforeach
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <a data-modal-toggle="add-nilai-modal-{{ $submission->id }}" data-tooltip-target="tooltip-nilai-{{ $submission->id }}" class="inline-flex items-center px-2 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    <svg class="w-4 h-4 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                        <path fill-rule="evenodd" d="M8 7V2.2a2 2 0 0 0-.5.4l-4 3.9a2 2 0 0 0-.3.5H8Zm2 0V2h7a2 2 0 0 1 2 2v.1a5 5 0 0 0-4.7 1.4l-6.7 6.6a3 3 0 0 0-.8 1.6l-.7 3.7a3 3 0 0 0 3.5 3.5l3.7-.7a3 3 0 0 0 1.5-.9l4.2-4.2V20a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V9h5a2 2 0 0 0 2-2Z" clip-rule="evenodd"/>
                        <path fill-rule="evenodd" d="M17.4 8a1 1 0 0 1 1.2.3 1 1 0 0 1 0 1.6l-.3.3-1.6-1.5.4-.4.3-.2Zm-2.1 2.1-4.6 4.7-.4 1.9 1.9-.4 4.6-4.7-1.5-1.5ZM17.9 6a3 3 0 0 0-2.2 1L9 13.5a1 1 0 0 0-.2.5L8 17.8a1 1 0 0 0 1.2 1.1l3.7-.7c.2 0 .4-.1.5-.3l6.6-6.6A3 3 0 0 0 18 6Z" clip-rule="evenodd"/>
                    </svg>
                    </a>
                    <a href="{{ route('submissionTugas.downloadSubmissionTugas', [$pelatihan->kode, $tugas->id, $submission->id]) }}" data-tooltip-target="tooltip-download-{{ $submission->id }}" class="inline-flex items-center px-2 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                    <svg class="w-4 h-4 text-white-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 13V4M7 14H5a1 1 0 0 0-1 1v4c0 .6.4 1 1 1h14c.6 0 1-.4 1-1v-4c0-.6-.4-1-1-1h-2m-1-5-4 5-4-5m9 8h0"/>
                    </svg>
                    </a>
                    <div id="tooltip-nilai-{{ $submission->id }}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-s font-small text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Nilai
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    <div id="tooltip-download-{{ $submission->id }}" role="tooltip" class="absolute z-10 invisible inline-block px-3 py-2 text-s font-small text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm opacity-0 tooltip dark:bg-gray-700">
                        Download
                        <div class="tooltip-arrow" data-popper-arrow></div>
                    </div>
                    
                    <!-- modal nilai -->
                    <div class="fixed left-0 right-0 z-50 items-center justify-center hidden overflow-x-hidden overflow-y-auto top-4 md:inset-0 h-modal sm:h-full"
                        id="add-nilai-modal-{{ $submission->id }}">
                        <div class="relative w-full h-full max-w-2xl px-4 md:h-auto">
                            <!-- Modal content -->
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                                <!-- Modal header -->
                                <div class="flex items-start justify-between p-3 border-b rounded-t dark:border-gray-700">
                                    <h1 class="text-xl font-semibold dark:text-white">
                                        Input nilai
                                    </h1>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                                        data-modal-toggle="add-nilai-modal-{{ $submission->id }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6 space-y-6">
                                    <form action="{{ route('submissionTugas.inputNilai', [$pelatihan->kode, $tugas->id, $submission->id]) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="grid grid-cols-6 gap-6">
                                            <div class="col-span-full">
                                                <input type="number" name="nilai" id="nilai" max="100" min="0" step="0.01" placeholder="Masukkan nilai"
                                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                                    required>
                                                @error('nilai')
                                                    <div class="invalid-feedback text-xs text-red-800 dark:text-red-400">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                            <div class="col-span-full">
                                                <button type="submit"
                                                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                                    data-modal-toggle="add-nilai-modal">
                                                    Save
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    @if ($errors->any())
                                        <div class="text-xs text-red-800 dark:text-red-400">
                                            @foreach ($errors->all() as $error)
                                                <div>{{ $error }}</div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
</div>
<script>
        document.addEventListener('DOMContentLoaded', function() {
    var table = document.getElementById('myTable');
    var rows = table.rows;
    var isSortAscendingNilai = true;
    var isSortAscendingNama = true;

    document.getElementById('sortNama').addEventListener('click', function() {
        sortByNama(); // sort by nama
    });
    document.getElementById('sortNilai').addEventListener('click', function() {
        sortByNilai(); // sort by nilai
    });

    function sortByNilai() {
        var sortedRows = Array.from(rows).slice(1).sort(function(a, b) {
            var cellA = Number(a.cells[2].innerText); // Mengambil nilai dari kolom nilai (indeks 2)
            var cellB = Number(b.cells[2].innerText); // Mengambil nilai dari kolom nilai (indeks 2)

            if (isNaN(cellA) || isNaN(cellB)) {
                // Jika salah satu nilai bukan angka, kembalikan 0 (tidak ada perubahan)
                return 0;
            }

            if (isSortAscendingNilai) {
                return cellA - cellB;
            } else {
                return cellB - cellA;
            }
        });

        // Menghapus semua baris kecuali header dari tabel
        while (table.rows.length > 1) {
            table.deleteRow(1);
        }

        // Menambahkan kembali baris-baris yang sudah diurutkan
        sortedRows.forEach(function(row) {
            table.appendChild(row);
        });

        isSortAscendingNilai = !isSortAscendingNilai;
    }

    function sortByNama() {
        var sortedRows = Array.from(rows).slice(1).sort(function(a, b) {
            var namaA = a.cells[0].innerText.toLowerCase();
            var namaB = b.cells[0].innerText.toLowerCase();

            if (isSortAscendingNama) {
                return namaA.localeCompare(namaB);
            } else {
                return namaB.localeCompare(namaA);
            }
        });

        // Menghapus semua baris kecuali header dari tabel
        while (table.rows.length > 1) {
            table.deleteRow(1);
        }

        // Menambahkan kembali baris-baris yang sudah diurutkan
        sortedRows.forEach(function(row) {
            table.appendChild(row);
        });

        isSortAscendingNama = !isSortAscendingNama;
    }
});
    </script>
@endsection
@endsection






