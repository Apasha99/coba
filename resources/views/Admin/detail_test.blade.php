@extends('admin.layout.layout_tabs')
@section('tabs')
<div class="text-sm font-medium text-center text-gray-500 border-b border-gray-200 dark:text-gray-400 dark:border-gray-700">
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
                        <a href="{{route('admin.viewDetailPelatihan', ['plt_kode'=>$test->plt_kode])}}"
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
                            <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Detail Tes</span>
                        </div>
                    </li>
                </ol>
            </nav>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <h1 class="text-2xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ $test->nama }}
            <span class="ml-2">
                @php
                    $startDate = new DateTime($test->start_date);
                    $endDate = new DateTime($test->end_date);
                    $now = new DateTime();
                @endphp
                @if($startDate < $now && $endDate > $now)
                    <span class="bg-green-200 text-green-900 text-sm font-medium px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-100">Aktif</span> 
                @elseif($startDate > $now)
                    <span class="bg-gray-500 text-white text-sm font-medium px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Belum mulai</span>
                @elseif($endDate < $now)
                    <span class="dark:bg-red-900 bg-red-300 text-red-900 text-sm font-medium px-2.5 py-0.5 rounded dark:text-red-200">Selesai</span>
                @endif 
            </span>
        </h1>
    </div>
<div class="relative overflow-x-auto sm:rounded-lg rounded-lg">
    <h3 class="text-m p-2 font-semibold text-gray-900 sm:text-lg dark:text-white mb-2">Detail Tes</h3>

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <tbody>
            <tr class="bg-zinc-100 border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Nama 
                </th>
                <td class="px-6 py-4">
                    {{$test->nama}}
                </td>
            </tr>
            <tr class="bg-zinc-100 border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    KKM 
                </th>
                <td class="px-6 py-4">
                    {{$test->kkm}}
                </td>
            </tr>
            <tr class="bg-zinc-100 border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Total Soal 
                </th>
                <td class="px-6 py-4">
                    {{$hitung_soal}}
                </td>
            </tr>
            <tr class="bg-zinc-100 border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Total Nilai 
                </th>
                <td class="px-6 py-4">
                    <?php
                        $nilai_setelah_pembulatan = round($hitung_nilai);
                        if ($nilai_setelah_pembulatan > 99.99) {
                            $nilai_setelah_pembulatan = 100;
                        }
                        echo $nilai_setelah_pembulatan;
                    ?>
                </td>
            </tr>
            <tr class="bg-zinc-100 border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Durasi
                </th>
                <td class="px-6 py-4">
                    {{ \Carbon\Carbon::parse($test->durasi)->format('H') }} Jam
                    {{ \Carbon\Carbon::parse($test->durasi)->format('i') }} Menit
                    {{ \Carbon\Carbon::parse($test->durasi)->format('s') }} Detik
                </td>

            </tr>
            <tr class="bg-zinc-100 border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Tanggal Mulai
                </th>
                <td class="px-6 py-4">
                    {{ \Carbon\Carbon::parse($test->start_date)->format('l, j F Y, h:i A') }}
                </td>
            </tr>
            <tr class="bg-zinc-100 border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Tanggal Selesai
                </th>
                <td class="px-6 py-4">
                    {{ \Carbon\Carbon::parse($test->end_date)->format('l, j F Y, h:i A') }}
                </td>
            </tr>
            <tr class="bg-zinc-100 border-b dark:bg-gray-800 dark:border-gray-700">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Tampilkan Review Test
                </th>
                <td class="px-6 py-4">
                    {{$test->tampil_hasil}}
                </td>
            </tr>
            <tr class="bg-zinc-100 dark:bg-gray-800">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Deskripsi
                </th>
                <td class="px-6 py-4">
                    {{ $test->deskripsi}}
                </td>
            </tr>
            @if ($existingNilai)
            <tr class="bg-zinc-100 dark:bg-gray-800">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Rekap Test
                </th>
                <td class="px-6 py-4 p-4 space-x-2 whitespace-nowrap ">
                    <a href="{{route('rekap.detailRekap', [$pelatihan->kode, $test->id])}}" class="text-blue-600 hover:text-blue-800 hover:underline">
                        Lihat Rekap
                    </a>
                </td>
            </tr>
            @endif
        </tbody>
    </table>
</div>
    <div class="mt-4 relative overflow-x-auto sm:rounded-lg">
        <h3 class="text-m font-semibold text-gray-900 sm:text-lg dark:text-white mb-2">Soal Tes</h3>
        <div class="col-span-full mt-4 mb-4">
            @if ($pelatihan->status != 'Completed')
            <a type="button" href = "{{route('soal.add', [$pelatihan->kode, $test->id])}}"
                class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <svg class="w-5 h-5 mr-2 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd"></path>
                </svg>
                Tambah Soal
            </a>
            @endif
        </div>
        <div class="relative overflow-x-auto shadow-md sm:rounded-lg rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 table-fixed dark:divide-gray-600 rounded-lg" id="myTable">
            <thead class="bg-indigo-600 dark:bg-indigo-700">
                <tr class="bg-indigo-600 dark:bg-indigo-700 dark:border-gray-700">
                    <th scope="col" class="border-r px-2 py-4 text-sm text-center text-white whitespace-nowrap dark:text-white">
                        Urutan
                    </th>
                    <th scope="col" class="border-r px-16 py-4 text-sm text-center text-white whitespace-nowrap dark:text-white">
                        Soal
                    </th>
                    <th scope="col" class="border-r px-16 py-4 text-sm text-center text-white whitespace-nowrap dark:text-white">
                        Tipe Soal
                    </th>
                    <th scope="col" class="border-r px-2 py-4 text-sm text-center text-white whitespace-nowrap dark:text-white">
                        Nilai
                    </th>
                    @if ($pelatihan->status != 'Completed')
                    <th scope="col" class="border-r px-4 py-4 text-sm text-white text-center whitespace-nowrap dark:text-white">
                        Actions
                    </th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($soal_test as $soal)
                    <tr class="bg-zinc-100 border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="border-r px-2 py-4 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $soal->urutan }}
                        </td>
                        <td class="border-r px-16 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $soal->title }}<br>
                            @if($soal->file_soal)
                                <img class="w-80 h-30" src="{{ asset('storage/' . $soal->file_soal) }}" alt="{{ $soal->id }}">
                            @else
                                <div></div> <!-- Placeholder jika tidak ada gambar -->
                            @endif
                            <br>
                            @php
                                // Assuming you have a way to determine dark mode, replace this with your logic
                                $isDarkMode = true; // logic to determine dark mode, e.g., fetching from user preferences

                                // Get all options for the current question
                                $options = $jawaban_test->where('soal_id', $soal->id)->shuffle();

                                // Initialize an array to store the randomized options
                                $randomizedOptions = [];

                                // Assign letters (A, B, C, etc.) to the randomized options
                                foreach ($options as $index => $jawaban) {
                                    $optionLetter = chr(65 + $index);
                                    $randomizedOptions[$optionLetter] = $jawaban;
                                }
                            @endphp

                            @foreach ($randomizedOptions as $optionLetter => $jawaban)
                                @php
                                    $textColor = $jawaban->status ? 'green' : ($isDarkMode ? 'gray' : 'black');
                                    $backgroundColor = $jawaban->status ? ($isDarkMode ? 'white' : 'black') : 'transparent';
                                @endphp
                                <span style="color: {{ $textColor }}; background-color: {{ $backgroundColor }}">
                                    {{ $optionLetter }}. {{ $jawaban->title }}
                                </span><br>
                            @endforeach

                            
                        </td>
                        <td class="border-r px-2 py-4 font-medium text-gray-900 text-center whitespace-nowrap dark:text-white">
                            {{ $soal->tipe }}
                        </td>
                        <td class="border-r px-2 py-4 font-medium text-gray-900 text-center whitespace-nowrap dark:text-white">
                            {{ $soal->nilai }}
                        </td>
                        @if ($pelatihan->status != 'Completed')
                        <td class="px-2 py-4 font-medium text-gray-900 text-center whitespace-nowrap dark:text-white">
                            <div class="relative lock justify-between mt-2">
                            @if($soal && $soal->id)
                                <a href="{{route('soal.edit', ['plt_kode'=>$test->plt_kode,'test_id'=>$test->id,'soal_id'=>$soal->id])}}" data-tooltip-target="tooltip-toggle-edit" class="inline-flex items-center px-2 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"></path>
                                        <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd"></path>
                                    </svg>
                                </a>
                                <div id="tooltip-toggle-edit" role="tooltip"
                                    class="absolute z-10 inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm tooltip opacity-0 invisible"
                                    style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(1107.33px, 60px, 0px);"
                                    data-popper-placement="bottom">
                                    Edit
                                    <div class="tooltip-arrow" data-popper-arrow=""
                                        style="position: absolute; left: 0px; transform: translate3d(68.6667px, 0px, 0px);"></div>
                                </div>
                            @endif
                                <button type="button" data-modal-toggle="delete-soal-modal-{{ $soal->id }}" data-tooltip-target="tooltip-toggle-delete"
                                    class="inline-flex items-center px-2 py-2 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-800 focus:ring-4 focus:ring-red-300 dark:focus:ring-red-900 ml-3">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                <div id="tooltip-toggle-delete" role="tooltip"
                                    class="absolute z-10 inline-block px-3 py-2 text-sm font-medium text-white transition-opacity duration-300 bg-gray-900 rounded-lg shadow-sm tooltip opacity-0 invisible"
                                    style="position: absolute; inset: 0px auto auto 0px; margin: 0px; transform: translate3d(1107.33px, 60px, 0px);"
                                    data-popper-placement="bottom">
                                    Delete
                                    <div class="tooltip-arrow" data-popper-arrow=""
                                        style="position: absolute; left: 0px; transform: translate3d(68.6667px, 0px, 0px);"></div>
                                </div>
                            </div>
                        </td>
                        @endif
                    </tr>
                    <div class="fixed left-0 right-0 z-50 items-center justify-center hidden top-8 md:inset-0 sm:h-50"
                        id="delete-soal-modal-{{ $soal->id }}">
                        <div class="relative w-50 h-50 max-w-2xl px-4 md:h-50">
                            <!-- Modal content -->
                            <div class="relative bg-zinc-100 rounded-lg shadow dark:bg-gray-800 overflow">
                                <!-- Modal header -->
                                <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                                    <h3 class="text-xl font-semibold dark:text-white">
                                        Hapus Soal
                                    </h3>
                                    <button type="button"
                                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                                        data-modal-toggle="delete-soal-modal-{{ $soal->id }}">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                            <path fill-rule="evenodd"
                                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </div>
                                <!-- Modal body -->
                                <div class="p-6 space-y-6 overflow-y-auto">
                                    <label class="block mb-2 text-sm font-medium text-gray-900 w-full dark:text-white p-2" for="delete">Apakah anda yakin ingin menghapus soal {{$soal->title}} beserta semua data jawabannya?</label>
                                    <div class="col-span-full flex justify-between">
                                        <form id="deleteForm" method="POST" action="{{route('soal.delete', ['plt_kode'=>$test->plt_kode, 'test_id'=>$test->id, 'soal_id'=>$soal->id])}}">
                                            @csrf
                                            @method('DELETE')
                                            <div class="flex">
                                                <button type="submit"
                                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800 mr-4"
                                                    data-modal-toggle="delete-soal-modal-{{ $soal->id }}">
                                                    Hapus
                                                </button>
                                                <button type="button"
                                                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-xs px-3 py-1.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                                    data-modal-toggle="delete-soal-modal-{{ $soal->id }}">
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
            </tbody>
        </table>
        </div>
    </div>
    <div class="fixed left-0 right-0 z-50 items-center justify-center hidden overflow-x-hidden overflow-y-auto top-4 md:inset-0 h-modal sm:h-full"
        id="add-soal-modal">
        <div class="relative w-full h-full max-w-2xl px-4 md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-zinc-100 rounded-lg shadow dark:bg-gray-800">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                    <h3 class="text-xl font-semibold dark:text-white">
                        Tambah Soal Baru
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                        data-modal-toggle="add-soal-modal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <form action="{{route('soal.store', ['plt_kode'=>$pelatihan->kode,'test_id'=>$test->id])}}" method="POST" enctype="multipart/form-data" id="your-form-id">
                        @csrf
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-full">
                                <label for="soal"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Soal</label>
                                <input type="text" name="soal" placeholder="Soal" id="soal"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required max="2000">
                                @error('soal')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="nilai"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nilai</label>
                                <input type="number" name="nilai" placeholder="Number" id="nilai"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required max="100" min="0">
                                @error('nilai')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="poster">Upload Foto Soal</label>
                                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                    aria-describedby="file_input_help" id="file_soal" name="file_soal" type="file" accept="image/*">
                                @error('file_soal')
                                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400" role="alert">
                                        {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="col-span-full">
                                <label for="tipe_option" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe</label>
                                <select required name="tipe_option" id="tipe-option" class="w-full mt-2 mb-2 block w-32 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" onchange="handleTipeSoalChange()">
                                    <option value="" selected disabled>Pilih tipe soal</option>
                                    <option value="Pilihan Ganda">Pilihan Ganda</option>
                                    <option value="Jawaban Singkat">Jawaban Singkat</option>
                                </select>
                                @error('tipe_option')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <div id="tipe-Jawaban Singkat" class="hidden mt-2">
                                    <!-- Opsi Jawaban Jawaban Singkat -->
                                    <div class="flex-col items-center mb-4">
                                        <label for="options" class="text-sm font-medium text-gray-900 dark:text-white">Opsi Jawaban</label>
                                        <div id="options-container-singkat">
                                            <!-- Default input opsi jawaban -->
                                            <div>
                                                <input type="text" name="jawaban-singkat" placeholder="Jawaban Benar 1" id="title-singkat"
                                                    class="mt-2 mb-3 shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            </div>
                                        </div>
                                        <button type="button" onclick="addOption('singkat')" class="block w-10 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">+</button>
                                    </div>
                                </div>

                                <div id="tipe-Pilihan Ganda" class="hidden mt-2">
                                    <!-- Opsi Jawaban Pilihan Ganda -->
                                    <div class="flex-col items-center mb-4">
                                        <label for="options" class="text-sm font-medium text-gray-900 dark:text-white">Opsi Jawaban</label>
                                        <div id="options-container-ganda1">
                                            <!-- Default input opsi jawaban -->
                                            <div>
                                                <input type="text" name="ganda-benar" placeholder="Jawaban Benar" id="title-benar"
                                                    class="mt-2 mb-3 shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            </div>
                                        </div>
                                        <div id="options-container-ganda2">
                                            <!-- Default input opsi jawaban -->
                                            <div>
                                                <input type="text" name="ganda" placeholder="Opsi Jawaban 1" id="title-ganda"
                                                    class="mt-2 mb-3 shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                            </div>
                                        </div>
                                        <button type="button" onclick="addOption('ganda2')" class="block w-10 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">+</button>
                                    </div>
                                </div>

                                <script>
                                    function handleTipeSoalChange() {
                                        const selectedTipe = document.getElementById('tipe-option').value;

                                        // Sembunyikan semua opsi jawaban
                                        document.getElementById('tipe-Jawaban Singkat').style.display = 'none';
                                        document.getElementById('tipe-Pilihan Ganda').style.display = 'none';

                                        // Tampilkan opsi jawaban sesuai dengan tipe yang dipilih
                                        if (selectedTipe === 'Pilihan Ganda') {
                                            document.getElementById('tipe-Pilihan Ganda').style.display = 'block';
                                        } else if (selectedTipe === 'Jawaban Singkat') {
                                            document.getElementById('tipe-Jawaban Singkat').style.display = 'block';
                                        }
                                    }
                                </script>

                                <script>
                                    let optionCounterGanda1 = 1;
                                    let optionCounterSingkat = 1;

                                    function addOption(tipe) {
                                        const form = document.getElementById('your-form-id'); // Replace 'your-form-id' with the actual ID of your form

                                        if (tipe === 'ganda2') {
                                            optionCounterGanda1++;
                                            const newOptionInput = document.createElement('div');
                                            newOptionInput.innerHTML = `
                                                <div class="flex items-center mb-3">
                                                    <input type="text" name="title_ganda[]" placeholder="Opsi Jawaban ${optionCounterGanda1}" 
                                                        class="flex-grow shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    <button type="button" onclick="removeOption(this)" class="ml-2 w-6 h-6 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg cursor-pointer focus:outline-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            `;
                                            document.getElementById('options-container-ganda2').appendChild(newOptionInput);
                                        } else if (tipe === 'singkat') {
                                            optionCounterSingkat++;
                                            const newOptionInput = document.createElement('div');
                                            newOptionInput.innerHTML = `
                                                <div class="flex items-center mb-3">
                                                    <input type="text" name="title_singkat[]" placeholder="Jawaban Benar ${optionCounterSingkat}" 
                                                        class="flex-grow shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    <button type="button" onclick="removeOption(this)" class="ml-2 w-6 h-6 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg cursor-pointer focus:outline-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            `;
                                            document.getElementById('options-container-singkat').appendChild(newOptionInput);
                                        }
                                    }

                                    function removeOption(button) {
                                        // Hapus elemen opsi jawaban saat tombol 'x' di klik
                                        const parentDiv = button.parentNode;
                                        parentDiv.parentNode.removeChild(parentDiv);
                                    }

                                    function submitForm() {
                                        const form = document.getElementById('your-form-id'); // Replace 'your-form-id' with the actual ID of your form
                                        form.submit();
                                    }

                                </script>

                            </div>

                            <!-- Tombol untuk menyimpan data -->
                            <div class="col-span-full">
                                <button type="submit"
                                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                    data-modal-toggle="add-soal-modal">
                                    Tambah Soal
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('tipe-option').addEventListener('change', function() {
            var tipeOption = this.value;
            if (tipeOption === 'Pilihan Ganda') {
                document.getElementById('tipe-Pilihan Ganda').classList.remove('hidden');
            } else if (tipeOption === 'Jawaban Singkat'){
                document.getElementById('tipe-Jawaban Singkat').classList.remove('hidden');
            }
            else {
                document.getElementById('export-range').classList.add('hidden');
            }
        });
    </script>
@endsection
@endsection







