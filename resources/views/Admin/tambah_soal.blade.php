@extends('admin.layout.layout_tabs')
@section('tabs')
<div class="text-sm font-medium text-center text-gray-500 border-gray-200 dark:text-gray-400 dark:border-gray-700">
    <ul class="flex flex-wrap -mb-px">
        <li class="me-2">
            <a href="{{ route('admin.viewDetailPelatihadn', $pelatihan->kode) }}" class="inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active dark:text-blue-500 dark:border-blue-500" aria-current="page">Pelatihan</a>
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
                <li class="flex items-center">
                    <a href="{{route('test.detail', [$pelatihan->kode, $test->id])}}"
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
                        <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Tambah Soal Test</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Tambah Soal Test</h1>
    </div>
    <!-- Right Content -->
    <div class="col-span-full xl:col-auto">
        <form action="{{ route('soal.store', [$pelatihan->kode, $test->id]) }}" method="post"  enctype="multipart/form-data">
            @csrf
            <div class="col-span-4">
                <div class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                        <h3 class="mb-4 text-xl font-semibold dark:text-white">Data Soal Test</h3>
                    
                        <div class="grid grid-cols-6 gap-6">
                                <div class="col-span-full">
                                    <label for="soal"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Soal <span class="text-red-500">*</span></label>
                                    <input type="text" name="soal" placeholder="Soal" id="soal"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        required max="2000">
                                    @error('soal')
                                    <div class="mt-2 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400"
                                        role="alert">
                                        <div>
                                            {{ $message }}
                                        </div>
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="tipe_nilai" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe Nilai <span class="text-red-500">*</span> </label>
                                    <select required name="tipe_nilai" id="tipe_nilai" class="w-full mt-2 mb-2 block w-32 
                                    text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 
                                    dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 
                                    dark:placeholder-gray-400" onchange="handleTipeNilai()">
                                        <option value="Default">Default</option>
                                        <option value="Custom">Custom</option>
                                    </select>
                                    <div id="tipe-Custom" class="hidden mt-2">
                                        <div class="flex-col items-center mb-2">
                                            <div id="nilais-container-custom">
                                                <div>
                                                    <input type="number" name="nilai-custom" placeholder="nilai" id="nilai-custom" min=1 max=50
                                                        class="mt-2 mb-2 shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @error('nilai')
                                    <div class="mt-2 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400"
                                        role="alert">
                                        <div>
                                            {{ $message }}
                                        </div>
                                    </div>
                                    @enderror
                                </div>

                                <script>
                                    function handleTipeNilai() {
                                        const selectedTipe = document.getElementById('tipe_nilai').value;

                                        // Sembunyikan semua opsi jawaban
                                        document.getElementById('tipe-Custom').style.display = 'none';

                                        // Tampilkan opsi jawaban sesuai dengan tipe yang dipilih
                                        if (selectedTipe === 'Custom') {
                                            document.getElementById('tipe-Custom').style.display = 'block';
                                        }
                                    }
                                </script>
                                <div class="col-span-6 sm:col-span-3">
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="poster">Upload Foto Soal</label>
                                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="file_input_help" id="file_soal" name="file_soal" type="file" accept="image/*">
                                    @error('file_soal')
                                    <div class="mt-2 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400"
                                        role="alert">
                                        <div>
                                            {{ $message }}
                                        </div>
                                    </div>
                                    @enderror
                                </div>
                                <div class="col-span-full">
                                    <label for="tipe_option" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe <span class="text-red-500">*</span></label>
                                    <select required name="tipe_option" id="tipe-option" class="w-full mt-2 mb-2 block w-32 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400" onchange="handleTipeSoalChange()">
                                        <option value="" selected disabled>Pilih tipe soal</option>
                                        <option value="Pilihan Ganda">Pilihan Ganda</option>
                                        <option value="Jawaban Singkat">Jawaban Singkat</option>
                                    </select>
                                    @error('tipe_option')
                                    <div class="mt-2 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400"
                                        role="alert">
                                        <div>
                                            {{ $message }}
                                        </div>
                                    </div>
                                    @enderror
                                    <div id="tipe-Jawaban Singkat" class="hidden mt-2">
                                        <div class="flex-col items-center mb-4">
                                            <label for="options" class="text-sm font-medium text-gray-900 dark:text-white">Opsi Jawaban <span class="text-red-500">*</span></label>
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
                                            <label for="options" class="text-sm font-medium text-gray-900 dark:text-white">Opsi Jawaban <span class="text-red-500">*</span></label>
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
                                                        <button type="button" onclick="removeOption(this)" class="ml-2 w-6 h-6 text-red-400 bg-transparent hover:bg-red-200 hover:text-red-900 rounded-lg cursor-pointer focus:outline-none">
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
                                                        <button type="button" onclick="removeOption(this)" class="ml-2 w-6 h-6 text-red-400 bg-transparent hover:bg-red-200 hover:text-red-900 rounded-lg cursor-pointer focus:outline-none">
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
                            <div class="col-span-6 sm:col-full mt-4 flex justify-end items-center">
                                <a href="{{ route('test.detail', [$test->plt_kode, $test->id]) }}"class="mr-4 text-white bg-gray-700 hover:bg-gray-800 focus:ring-4 focus:ring-gray-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-gray-600 dark:hover:bg-gray-700 dark:focus:ring-gray-800">
                                    Batal
                                </a>
                                <button class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800" type="submit">Simpan</button>
                            </div>
                        </div>
                </div>
            </div>
        </form>
    </div>
@endsection