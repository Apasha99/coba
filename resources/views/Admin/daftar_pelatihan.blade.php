@extends('admin.layout.layout')

@section('content')
    <div class="col-span-full xl:col-auto">
    <!-- <div class="m-auto">
        <a href="{{ route('admin.addPelatihan') }}" data-modal-toggle="add-pelatihan-modal"
        class="open-modal-button mr-auto text-white bg-green-400 hover:bg-green-500 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-400 dark:hover:bg-green-500 focus:outline-none dark:focus:ring-green-600" type="button">
            + Tambah Pelatihan
        </a>
    </div> -->
    <div class="m-auto">
        <button type="button" data-modal-toggle="add-pelatihan-modal"
            class="inline-flex items-center justify-center w-1/2 px-3 py-2 text-sm font-medium text-center text-white rounded-lg bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 sm:w-auto dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">
            <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd"
                    d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                    clip-rule="evenodd"></path>
            </svg>
            Add mahasiswa
        </button>
    </div>
    <div class="flex flex-wrap">
    @foreach ($pelatihan as $plt)
        <a href="{{ route('admin.viewDetailPelatihan', $plt->kode) }}" class="block mt-4 w-48 h-48 p-4 mb-4 mr-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <div class="items-center sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4">
                <div>
                    <h3 class="mb-1 text-l font-bold text-gray-900 dark:text-white">{{ $plt->nama }}</h3>
                    <div class="mb-4 text-sm text-gray-500 dark:text-gray-400">
                        <p>{{ $plt->status }}</p> 
                    </div>
                </div>
            </div>
        </a>
    @endforeach
</div>



        <!-- <div
            class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <svg class="w-6 h-6 dark:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-base font-semibold text-gray-900 truncate dark:text-white">
                        Fakultas
                    </p>
                    <p class="text-sm font-normal text-gray-500 truncate dark:text-gray-400">
                        Sains dan Matematika
                    </p>
                </div>
            </div> -->
        </div>
    </div>

    <div class="fixed left-0 right-0 z-50 items-center justify-center hidden overflow-x-hidden overflow-y-auto top-4 md:inset-0 h-modal sm:h-full"
        id="add-pelatihan-modal">
        <div class="relative w-full h-full max-w-2xl px-4 md:h-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                    <h3 class="text-xl font-semibold dark:text-white">
                        Tambah Pelatihan
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                        data-modal-toggle="add-pelatihan-modal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6">
                    <form action="{{ route('admin.storePelatihan') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                <label for="kode"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode</label>
                                <input type="text" name="kode" placeholder="Kode" id="kode"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                                @error('kode')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="nama"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama Pelatihan</label>
                                <input type="text" name="nama" placeholder="Nama Pelatihan" id="nama"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                                @error('nama')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="start_date"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Mulai Pelatihan</label>
                                <input type="date" name="start_date" placeholder="Tanggal Mulai Pelatihan" id="start_date"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                                @error('start_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="end_date"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Selesai Pelatihan</label>
                                <input type="date" name="end_date" placeholder="Tanggal Selesai Pelatihan" id="end_date"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                                @error('end_date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-span-6 sm:col-span-3">
                                <label for="status"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                <select name="status" id="status"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                                    <option selected disabled>Pilih Status</option>
                                    <option value="Not started yet">Not started yet</option>
                                    <option value="On going">On going</option>
                                    <option value="Completed">Completed</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="penyelenggara"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Penyelenggara</label>
                                <input type="text" name="penyelenggara" placeholder="Penyelenggara" id="penyelenggara"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                                @error('penyelenggara')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="tempat"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tempat Pelatihan</label>
                                <select name="tempat" id="tempat"
                                    class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                                    <option selected disabled>Pilih Tempat</option>
                                    <option value="Ruang Lakakrida Lt.B - Gedung Moch Ichsan Lantai 8">Ruang Lakakrida Lt.B - Gedung Moch Ichsan Lantai 8</option>
                                    <option value="Gedung Balaikota">Gedung Balaikota</option>
                                    <option value="Ruang Komisi A-B Gedung Moch.Ichsan Lantai 8">Ruang Komisi A-B Gedung Moch.Ichsan Lantai 8</option>
                                    <option value="Gedung Juang 45">Gedung Juang 45</option>
                                    <option value="Ruang Komisi C-D Gedung Moch.Ichsan Lantai 8">Ruang Komisi C-D Gedung Moch.Ichsan Lantai 8</option>
                                    <option value="Ruang Rapat Lantai 4">Ruang Rapat Lantai 4</option>
                                    <option value="Hall Balaikota Semarang">Hall Balaikota Semarang</option>
                                    <option value="Halaman Balaikota Semarang">Halaman Balaikota Semarang</option>
                                    <option value="Ruang Rapat Lantai 6 Siber Pungli">Ruang Rapat Lantai 6 Siber Pungli</option>
                                </select>
                                @error('tempat')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="col-span-6 sm:col-span-3">
                                <label for="deskripsi"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                                <input type="text" name="deskripsi" placeholder="Deskripsi (max:255 kata)" id="deskripsi"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                                @error('deskripsi')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <!-- Tombol untuk menyimpan data -->
                            <div class="col-span-full">
                                <button type="submit"
                                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                    data-modal-toggle="add-pelatihan-modal">
                                    Tambah Pelatihan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    



    
@endsection





