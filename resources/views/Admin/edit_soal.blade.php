@extends('admin.layout.layout_tabs')
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
@endsection
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
                <li class="flex items-center">
                    <a href="{{route('test.detail', ['plt_kode'=>$test->plt_kode, 'test_id'=>$test->id])}}"
                        class="inline-flex items-center text-gray-700 hover:text-primary-600 dark:text-gray-300 dark:hover:text-white">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Detail Test
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
                        <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Edit Soal Test</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Edit Soal Test</h1>
    </div>
    <!-- Right Content -->
    <div class="col-span-full xl:col-auto">
        <form action="{{ route('soal.update', ['plt_kode' => $test->plt_kode,'test_id'=>$test->id,'soal_id'=>$soal_test->id]) }}" method="post" id="edit-id">
            @csrf
            <div class="col-span-4">
                <div
                    class="p-2 mb-2 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                    <h3 class="mb-4 text-xl font-semibold dark:text-white">General information</h3>
                    
                        <div class="grid grid-cols-6 gap-3">
                            <div class="col-span-full">
                                <label for="urutan"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Urutan <span class="text-red-500">*</span></label>
                                <input type="number" name="urutan" placeholder="urutan" id="urutan" value="{{ $soal_test->urutan }}" disabled
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('urutan')
                                <div class="p-2 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-span-full">
                                <label for="soal"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Soal <span class="text-red-500">*</span></label>
                                <input type="text" name="soal" placeholder="soal" id="soal" value="{{ $soal_test->title }}" 
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('soal')
                                <div class="p-2 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="nilai"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nilai <span class="text-red-500">*</span></label>
                                <input type="number" name="nilai" placeholder="nilai" id="nilai" value="{{ $soal_test->nilai }}" max="100" min="1"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <span class="mt-2 text-sm text-red-800 bg-red-100 dark:bg-gray-800 dark:text-red-400">
                                    Sisa Nilai = 
                                    @if ($hitung_nilai == 0)
                                        100
                                    @else
                                        {{ 100 - $hitung_nilai }}
                                    @endif
                                </span>
                                @error('nilai')
                                <div class="p-2 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            @php
                                $nama_file = basename($soal_test->file_soal)
                            @endphp

                            <div class="col-span-6 sm:col-span-3">
                                <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="poster">Upload Foto Soal</label>
                                <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                    aria-describedby="file_input_help" id="file_soal" name="file_soal" type="file" accept="image/*">
                                @if($soal_test->file_soal)
                                    <div class="mt-1 text-sm text-gray-500">
                                        File sebelumnya: {{ $nama_file }}
                                    </div>
                                @endif
                                @error('file_soal')
                                    <div class="p-1 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
     
                            <div class="col-span-full">
                                <label for="tipe_option" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe <span class="text-red-500">*</span></label>
                                <select disabled required name="tipe_option" id="tipe-option" class="w-full mt-2 mb-2 block w-32 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600">
                                    <option value="" selected disabled>Pilih tipe soal</option>
                                    <option value="Pilihan Ganda" {{ $soal_test->tipe == 'Pilihan Ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                                    <option value="Jawaban Singkat" {{ $soal_test->tipe == 'Jawaban Singkat' ? 'selected' : '' }}>Jawaban Singkat</option>
                                </select>
                                <input type="hidden" name="tipe_option" value="{{ $soal_test->tipe }}">

                                @error('tipe_option')
                                    <div class="p-1 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                                @if($jawaban_test)
                                <div id="tipe-Jawaban Singkat" class="hidden mt-2">
                                    <!-- Opsi Jawaban Jawaban Singkat -->
                                    <div class="flex-col items-center mb-4">
                                        <label for="options" class="text-sm font-medium text-gray-900 dark:text-white">Opsi Jawaban <span class="text-red-500">*</span></label>
                                        <div id="options-container-singkat">
                                            <!-- Default input opsi jawaban -->
                                            @foreach($jawaban_test as $jawaban)
                                            <div id="jawaban-container-{{ $jawaban->id }}">
                                                <div class="flex items-center">
                                                    @if ($soal_test->tipe == "Jawaban Singkat")
                                                        <input type="text" name="title_singkat[]" placeholder="Jawaban Benar {{ $jawaban->urutan }}" id="title-singkat-{{$jawaban->id}}"
                                                            value="{{ $jawaban->title }}"
                                                            class="mt-2 mb-1 shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                        @if (!$loop->first) <!-- Cek apakah bukan jawaban pertama -->
                                                            <button type="button" title="Hapus Jawaban" onclick="deleteJawaban('{{route('jawaban.delete', ['plt_kode' => $test->plt_kode,'test_id'=>$test->id,'soal_id'=>$soal_test->id,'jawaban_id'=>$jawaban->id])}}', 'jawaban-container-{{ $jawaban->id }}')" class="ml-2 w-6 h-6 text-red-400 bg-transparent hover:bg-red-200 hover:text-red-900 rounded-lg cursor-pointer focus:outline-none">
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    @endif
                                                </div>
                                                <p class="mt-1 mb-1 text-sm text-gray-500 dark:text-gray-300">Jawaban Benar</p>
                                            </div>
                                            @endforeach

                                            <!-- Default input opsi jawaban -->
                                        </div>
                                        <button type="button" onclick="addOption('singkat')" class="block w-10 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">+</button>
                                    </div>
                                </div>
                                
                                <div id="tipe-Pilihan Ganda" class="hidden mt-2">
                                    <!-- Opsi Jawaban Pilihan Ganda -->
                                    <div class="flex-col items-center mb-4">
                                        <label for="options" class="text-sm font-medium text-gray-900 dark:text-white">Opsi Jawaban <span class="text-red-500">*</span></label>
                                        <div id="options-container-ganda1">
                                            <!-- Default input opsi jawaban -->
                                            <div >
                                                @if ($soal_test->tipe == "Pilihan Ganda")
                                                <input type="text" name="ganda_benar" placeholder="Jawaban Benar" id="title-benar"
                                                    value="{{ $jawaban_test->where('status', true)->first()->title }}"
                                                    class="mt-2 shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                @endif
                                                <p class="mt-1 mb-3 text-sm text-gray-500 dark:text-gray-300">Jawaban Benar
                                                </p>
                                            </div>
                                        </div>
                                        <div id="options-container-ganda2">
                                            <!-- Default input opsi jawaban -->
                                            @foreach($jawaban_test->where('status', false) as $jawaban)
                                                @if ($soal_test->tipe == "Pilihan Ganda")
                                                <div id="jawaban-container-{{ $jawaban->id }}">
                                                    <div class="flex items-center">
                                                        <input type="text" name="title_ganda[]" placeholder="Opsi Jawaban {{ $jawaban->urutan }}" id="title-ganda-{{$jawaban->id}}"
                                                            value="{{ $jawaban->title }}"
                                                            class="flex-grow shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                        @if (!$loop->first)
                                                        <button type="button" title="Hapus Jawaban" onclick="deleteJawaban('{{route('jawaban.delete', ['plt_kode' => $test->plt_kode,'test_id'=>$test->id,'soal_id'=>$soal_test->id,'jawaban_id'=>$jawaban->id])}}', 'jawaban-container-{{ $jawaban->id }}')" class="ml-2 w-6 h-6 text-red-400 bg-transparent hover:bg-red-200 hover:text-red-900 rounded-lg cursor-pointer focus:outline-none">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                            </svg>
                                                        </button>
                                                        @endif
                                                    </div>
                                                    <p class="mt-1 mb-2 text-sm text-gray-500 dark:text-gray-300">Jawaban Salah
                                                    </p>
                                                </div>
                                                @endif
                                            @endforeach
                                            <!-- Default input opsi jawaban -->
                                        </div>
                                        <button type="button" onclick="addOption('ganda2')" class="block w-10 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400">+</button>
                                    </div>
                                </div>
                                @endif
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
                                    function deleteJawaban(route, containerId) {
                                        // Hapus elemen dari DOM
                                        const container = document.getElementById(containerId);
                                        if (container) {
                                            container.remove();
                                        }

                                        // Lakukan juga pengiriman permintaan ke server untuk menghapus data jawaban secara permanen
                                        fetch(route, {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            },
                                        }).then(response => {
                                            if (!response.ok) {
                                                console.error('Failed to delete Jawaban');
                                            }
                                        }).catch(error => {
                                            console.error('Error during delete request:', error);
                                        });
                                    }
                                </script>


                                <script>
                                    function addOption(tipe) {
                                        const form = document.getElementById('edit-id'); // Replace 'your-form-id' with the actual ID of your form

                                        if (tipe === 'ganda2') {
                                            // Mendapatkan jumlah elemen dengan ID yang dimulai dengan 'jawaban-'
                                            const jumlahJawaban = document.querySelectorAll('[id^="jawaban-"]').length;
                                            const newOptionInput = document.createElement('div');
                                            newOptionInput.innerHTML = `
                                                <div class="flex items-center" id="jawaban-${jumlahJawaban}">
                                                    <input type="text" name="title_ganda[]" placeholder="Opsi Jawaban" 
                                                        class="flex-grow shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    <button type="button" onclick="deleteJawaban('{{route('jawaban.delete', ['plt_kode' => $test->plt_kode,'test_id'=>$test->id,'soal_id'=>$soal_test->id,'jawaban_id'=>$jawaban->id])}}', ${jumlahJawaban})" class="ml-2 w-6 h-6 text-red-400 bg-transparent hover:bg-red-200 hover:text-red-900 rounded-lg cursor-pointer focus:outline-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <p class="mt-1 mb-2 text-sm text-gray-500 dark:text-gray-300">Jawaban Salah
                                                </p>
                                            `;
                                            document.getElementById('options-container-ganda2').appendChild(newOptionInput);
                                        } else if (tipe === 'singkat') {
                                            const jumlahJawaban = document.querySelectorAll('[id^="jawaban-"]').length;
                                            const newOptionInput = document.createElement('div');
                                            newOptionInput.innerHTML = `
                                                <div class="flex items-center" id="jawaban-${jumlahJawaban}">
                                                    <input type="text" name="title_singkat[]" placeholder="Jawaban Benar" 
                                                        class="flex-grow shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    <button type="button" onclick="deleteJawaban('{{route('jawaban.delete', ['plt_kode' => $test->plt_kode,'test_id'=>$test->id,'soal_id'=>$soal_test->id,'jawaban_id'=>$jawaban->id])}}', ${jumlahJawaban})" class="ml-2 w-6 h-6 text-red-400 bg-transparent hover:bg-red-200 hover:text-red-900 rounded-lg cursor-pointer focus:outline-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                                <p class="mt-1 mb-1 text-sm text-gray-500 dark:text-gray-300">Jawaban Benar
                                                </p>
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
                                        const form = document.getElementById('edit-id'); // Replace 'your-form-id' with the actual ID of your form
                                        form.submit();
                                    }

                                    function deleteJawaban(route, containerId) {
                                        // Hapus elemen dari DOM
                                        const container = document.getElementById(containerId);
                                        if (container) {
                                            container.remove();
                                        }
                                    }



                                </script>

                            </div>

                            <!-- Tombol untuk menyimpan data -->
                            <div class="col-span-6 sm:col-full mt-4 flex justify-end items-center">
                                <a href="{{ route('test.detail', [$test->plt_kode, $test->id]) }}"class="mr-4 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                    Batal
                                </a>
                                <button type="submit" onclick="submitForm()"
                                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                    >
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tipeOption = document.getElementById('tipe-option').value;
            showAnswerOptions(tipeOption);
        });

        document.getElementById('tipe-option').addEventListener('change', function() {
            var tipeOption = this.value;
            showAnswerOptions(tipeOption);
        });

        function showAnswerOptions(tipeOption) {
            // Sembunyikan semua opsi jawaban
            document.getElementById('tipe-Jawaban Singkat').classList.add('hidden');
            document.getElementById('tipe-Pilihan Ganda').classList.add('hidden');

            // Tampilkan opsi jawaban sesuai dengan tipe yang dipilih
            if (tipeOption === 'Pilihan Ganda') {
                document.getElementById('tipe-Pilihan Ganda').classList.remove('hidden');
            } else if (tipeOption === 'Jawaban Singkat') {
                document.getElementById('tipe-Jawaban Singkat').classList.remove('hidden');
            }
        }
    </script>

@endsection