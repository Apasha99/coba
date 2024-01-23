@extends('admin.layout.layout')

@section('content')
    <div class="mb-4 col-span-full xl:mb-2">
        <h1 class="text-2xl font-semibold text-gray-900 sm:text-2xl dark:text-white">{{ $test->nama }}</h1>
    </div>
    <div class="mb-4 col-span-full xl:mb-2">
        <p class="text-sm font-semibold text-gray-900 sm:text-sm dark:text-white">
            Status : 
            <span class="text-sm font-normal sm:text-sm dark:text-white" style="background-color: {{ $test->isActive == 1 ? 'green' : 'red' }}; color: white;">
                @if($test->isActive == 1)
                    Aktif
                @else
                    Tidak Aktif
                @endif
            </span>

        </p>
    </div>
<div class="relative overflow-x-auto shadow-md sm:rounded-lg">
    <h3 class="text-m font-semibold text-gray-900 sm:text-lg dark:text-white mb-2">Detail Test</h3>

    <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
        <tbody>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Nama 
                </th>
                <td class="px-6 py-4">
                    {{$test->nama}}
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Tanggal Mulai
                </th>
                <td class="px-6 py-4">
                    {{ \Carbon\Carbon::parse($test->start_date)->format('l, j F Y, h:i A') }}
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Tanggal Selesai
                </th>
                <td class="px-6 py-4">
                    {{ \Carbon\Carbon::parse($test->end_date)->format('l, j F Y, h:i A') }}
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Acak Soal
                </th>
                <td class="px-6 py-4">
                    {{$test->acak_soal}}
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Acak Jawaban
                </th>
                <td class="px-6 py-4">
                    {{$test->acak_jawaban}}
                </td>
            </tr>
            <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Tampilkan Hasil
                </th>
                <td class="px-6 py-4">
                    {{$test->tampil_hasil}}
                </td>
            </tr>
            <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600">
                <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                    Deskripsi
                </th>
                <td class="px-6 py-4">
                    {{ $test->deskripsi}}
                </td>
            </tr>
        </tbody>
    </table>
</div>
    <div class="mt-4 relative overflow-x-auto shadow-md sm:rounded-lg">
        <h3 class="text-m font-semibold text-gray-900 sm:text-lg dark:text-white mb-2">Soal Test</h3>
        <div class="col-span-full mt-4 mb-4">
            <a type="button" data-modal-toggle="add-soal-modal"
                class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
                <svg class="w-5 h-5 mr-2 ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                        clip-rule="evenodd"></path>
                </svg>
                Tambah
            </a>
        </div>
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <tbody>
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    <th scope="col" class="border-r px-2 py-4 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">
                        Urutan
                    </th>
                    <th scope="col" class="border-r px-16 py-4 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">
                        Soal
                    </th>
                    <th scope="col" class="border-r px-2 py-4 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">
                        Nilai
                    </th>
                    <th scope="col" class="px-4 py-4 font-medium text-gray-900 text-center whitespace-nowrap dark:text-white">
                        Actions
                    </th>
                </tr>
                @foreach ($soal_test as $soal)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="border-r px-2 py-4 font-medium text-center text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $soal->urutan }}
                        </td>
                        <td class="border-r px-16 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $soal->title }}<br>
                            @if($soal->file_soal)
                                <img class="w-80 h-30" src="{{ asset('storage/' . $soal->file_soal) }}" alt="{{ $soal->urutan }}">
                            @else
                                <div class="w-10 h-10 bg-gray-300 rounded-full"></div> <!-- Placeholder jika tidak ada gambar -->
                            @endif
                            <br>
                            @php
                                // Assuming you have a way to determine dark mode, replace this with your logic
                                $isDarkMode = true; // logic to determine dark mode, e.g., fetching from user preferences
                            @endphp

                            @foreach ($jawaban_test->where('soal_id', $soal->id) as $jawaban)
                                @php
                                    $option = chr(65 + $jawaban->urutan - 1); // Convert to A, B, C, etc.
                                    $textColor = $jawaban->status ? 'green' : ($isDarkMode ? 'gray' : 'black');
                                    $backgroundColor = $jawaban->status ? ($isDarkMode ? 'white' : 'black') : 'transparent';
                                @endphp
                                <span style="color: {{ $textColor }}; background-color: {{ $backgroundColor }}">{{ $option }}. {{ $jawaban->title }}</span><br>
                            @endforeach



                        </td>
                        <td class="px-2 py-4 font-medium text-gray-900 text-center whitespace-nowrap dark:text-white">
                            {{ $soal->nilai }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>




    </div>
    <div class="fixed left-0 right-0 z-50 items-center justify-center hidden overflow-x-hidden overflow-y-auto top-4 md:inset-0 h-modal sm:h-full"
        id="add-soal-modal">
        <div class="relative w-full h-full max-w-2xl px-4 md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
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
                    <form action="{{route('admin.storeSoal', ['plt_kode'=>$pelatihan->kode,'test_id'=>$test->id])}}" method="POST" enctype="multipart/form-data" id="your-form-id">
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







