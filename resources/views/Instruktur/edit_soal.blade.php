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
                <li class="flex items-center">
                    <a href="{{route('instruktur.viewDaftarPelatihan')}}"
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
                    <a href="{{route('instruktur.viewDetailPelatihan', ['plt_kode'=>$test->plt_kode])}}"
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
                    class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                    <h3 class="mb-4 text-xl font-semibold dark:text-white">General information</h3>
                    
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-full">
                                <label for="urutan"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Urutan</label>
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
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Soal</label>
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
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nilai</label>
                                <input type="number" name="nilai" placeholder="nilai" id="nilai" value="{{ $soal_test->nilai }}"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                @error('nilai')
                                <div class="p-2 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400" role="alert">
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
                                    <option value="Pilihan Ganda" {{ $soal_test->tipe == 'Pilihan Ganda' ? 'selected' : '' }}>Pilihan Ganda</option>
                                    <option value="Jawaban Singkat" {{ $soal_test->tipe == 'Jawaban Singkat' ? 'selected' : '' }}>Jawaban Singkat</option>
                                </select>
                                @error('tipe_option')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                                @if($jawaban_test)
                                <div id="tipe-Jawaban Singkat" class="hidden mt-2">
                                    <!-- Opsi Jawaban Jawaban Singkat -->
                                    <div class="flex-col items-center mb-4">
                                        <label for="options" class="text-sm font-medium text-gray-900 dark:text-white">Opsi Jawaban</label>
                                        <div id="options-container-singkat">
                                            <!-- Default input opsi jawaban -->
                                            @foreach($jawaban_test as $jawaban)
                                                <div class="flex items-center mb-1" id="jawaban-container-{{ $jawaban->id }}">
                                                    @if ($soal_test->tipe == "Jawaban Singkat")
                                                    <input type="text" name="title_singkat[]" placeholder="Jawaban Benar {{ $jawaban->urutan }}" id="title-singkat"
                                                        value="{{ $jawaban->title }}"
                                                        class="mt-2 mb-1 shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    <button type="button" onclick="deleteJawaban('{{route('jawaban.delete', ['plt_kode' => $test->plt_kode,'test_id'=>$test->id,'soal_id'=>$soal_test->id,'jawaban_id'=>$jawaban->id])}}')" class="ml-2 w-6 h-6 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg cursor-pointer focus:outline-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                    @endif
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
                                        <label for="options" class="text-sm font-medium text-gray-900 dark:text-white">Opsi Jawaban</label>
                                        <div id="options-container-ganda1">
                                            <!-- Default input opsi jawaban -->
                                            <div >
                                                @if ($soal_test->tipe == "Pilihan Ganda")
                                                <input type="text" name="ganda_benar" placeholder="Jawaban Benar" id="title-benar"
                                                    value="{{ $jawaban_test->where('status', true)->first()->title }}"
                                                    class="mt-2 mb-3 shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                @endif
                                            </div>
                                        </div>
                                        <div id="options-container-ganda2">
                                            <!-- Default input opsi jawaban -->
                                            @foreach($jawaban_test->where('status', false) as $jawaban)
                                                @if ($soal_test->tipe == "Pilihan Ganda")
                                                <div class="flex items-center mb-3" id="jawaban-{{ $jawaban->id }}">
                                                    <input type="text" name="title_ganda[]" placeholder="Opsi Jawaban {{ $jawaban->urutan }}" id="title-ganda"
                                                        value="{{ $jawaban->title }}"
                                                        class="flex-grow shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    <button type="button" onclick="deleteJawaban('{{route('jawaban.delete', ['plt_kode' => $test->plt_kode,'test_id'=>$test->id,'soal_id'=>$soal_test->id,'jawaban_id'=>$jawaban->id])}}')" class="ml-2 w-6 h-6 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg cursor-pointer focus:outline-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
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
                                    function deleteJawaban(url, jawabanId) {
                                        fetch(url, {
                                            method: 'POST',
                                            headers: {
                                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                            },
                                        }).then(response => {
                                            if (response.ok) {
                                                // Remove the deleted element from the DOM
                                                const deletedElement = document.getElementById('jawaban-container-' + jawabanId);
                                                if (deletedElement) {
                                                    deletedElement.remove();
                                                }

                                            } else {
                                                // Handle failure, e.g., show an error message
                                                console.error('Failed to delete Jawaban');
                                            }
                                        }).catch(error => {
                                            // Handle network error or other issues
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
                                                <div class="flex items-center mb-3" id="jawaban-${jumlahJawaban}">
                                                    <input type="text" name="title_ganda[]" placeholder="Opsi Jawaban" 
                                                        class="flex-grow shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    <button type="button" onclick="deleteJawaban('{{route('jawaban.delete', ['plt_kode' => $test->plt_kode,'test_id'=>$test->id,'soal_id'=>$soal_test->id,'jawaban_id'=>$jawaban->id])}}', ${jumlahJawaban})" class="ml-2 w-6 h-6 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg cursor-pointer focus:outline-none">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                        </svg>
                                                    </button>
                                                </div>
                                            `;
                                            document.getElementById('options-container-ganda2').appendChild(newOptionInput);
                                        } else if (tipe === 'singkat') {
                                            const jumlahJawaban = document.querySelectorAll('[id^="jawaban-"]').length;
                                            const newOptionInput = document.createElement('div');
                                            newOptionInput.innerHTML = `
                                                <div class="flex items-center mb-3" id="jawaban-${jumlahJawaban}">
                                                    <input type="text" name="title_singkat[]" placeholder="Jawaban Benar" 
                                                        class="flex-grow shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                                    <button type="button" onclick="deleteJawaban('{{route('jawaban.delete', ['plt_kode' => $test->plt_kode,'test_id'=>$test->id,'soal_id'=>$soal_test->id,'jawaban_id'=>$jawaban->id])}}', ${jumlahJawaban})" class="ml-2 w-6 h-6 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg cursor-pointer focus:outline-none">
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
                                        const form = document.getElementById('edit-id'); // Replace 'your-form-id' with the actual ID of your form
                                        form.submit();
                                    }


                                </script>

                            </div>

                            <!-- Tombol untuk menyimpan data -->
                            <div class="col-span-full">
                                <button type="submit" onclick="submitForm()"
                                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                    >
                                    Save
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