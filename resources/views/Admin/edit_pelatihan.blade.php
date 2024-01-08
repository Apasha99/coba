@extends('admin.layout.layout')

@section('content')
    <div class="mb-4 col-span-full xl:mb-2">
        <nav class="flex mb-5" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 text-sm font-medium md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="/dashboardAdmin"
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
                <li>
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="ml-1 text-gray-400 md:ml-2 dark:text-gray-500" aria-current="page">Edit Pelatihan</span>
                    </div>
                </li>
            </ol>
        </nav>
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Edit peserta_pelatihan</h1>
    </div>
    <!-- Right Content -->
    <div class="col-span-full xl:col-auto">
        <form action="{{ route('admin.updatePelatihan', ['plt_id' => $plt->id]) }}" method="post">
            @csrf
        </div>
        <div class="col-span-4">
                <div
                    class="p-4 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm 2xl:col-span-2 dark:border-gray-700 sm:p-6 dark:bg-gray-800">
                    <h3 class="mb-4 text-xl font-semibold dark:text-white">General information</h3>
                    
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-3">
                                    <label for="kode"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode</label>
                                    <input type="text" name="kode" placeholder="kode" id="kode" value="{{ $plt->kode }}" disabled
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="nama"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Nama</label>
                                    <input type="text" name="nama" placeholder="nama" id="nama" value="{{ $plt->nama }}"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="start_date"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Mulai Pelatihan</label>
                                    <input type="date" name="start_date" placeholder="start_date" id="start_date" value="{{ $plt->start_date }}"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="end_date"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tanggal Selesai Pelatihan</label>
                                    <input type="date" name="end_date" placeholder="end_date" id="end_date" value="{{ $plt->end_date }}"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="status"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                    <select name="status" id="status"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option disabled>Pilih Status</option>
                                        <option value="Not started yet" {{ $plt->status == 'Not started yet' ? 'selected' : '' }}>Not started yet</option>
                                        <option value="On going" {{ $plt->status == 'On going' ? 'selected' : '' }}>On going</option>
                                        <option value="Completed" {{ $plt->status == 'Completed' ? 'selected' : '' }}>Completed</option>
                                    </select>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="penyelenggara"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Penyelenggara</label>
                                    <input type="text" name="penyelenggara" placeholder="penyelenggara" id="penyelenggara" value="{{$plt->penyelenggara}}"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="tempat"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tempat</label>
                                    <select name="tempat" id="tempat"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                        <option selected disabled>Pilih Tempat</option>
                                        <option value="Ruang Lakakrida Lt.B - Gedung Moch Ichsan Lantai 8" {{ $plt->tempat == 'Ruang Lakakrida Lt.B - Gedung Moch Ichsan Lantai 8' ? 'selected' : '' }}>Ruang Lakakrida Lt.B - Gedung Moch Ichsan Lantai 8</option>
                                        <option value="Gedung Balaikota" {{ $plt->tempat == 'Gedung Balaikota' ? 'selected' : '' }}>Gedung Balaikota</option>
                                        <option value="Ruang Komisi A-B Gedung Moch.Ichsan Lantai 8" {{ $plt->tempat == 'Ruang Komisi A-B Gedung Moch.Ichsan Lantai 8' ? 'selected' : '' }}>Ruang Komisi A-B Gedung Moch.Ichsan Lantai 8</option>
                                        <option value="Gedung Juang 45" {{ $plt->tempat == 'Gedung Juang 45' ? 'selected' : '' }}>Gedung Juang 45</option>
                                        <option value="Ruang Komisi C-D Gedung Moch.Ichsan Lantai 8" {{ $plt->tempat == 'Ruang Komisi C-D Gedung Moch.Ichsan Lantai 8' ? 'selected' : '' }}>Ruang Komisi C-D Gedung Moch.Ichsan Lantai 8</option>
                                        <option value="Ruang Rapat Lantai 4" {{ $plt->tempat == 'Ruang Rapat Lantai 4' ? 'selected' : '' }}>Ruang Rapat Lantai 4</option>
                                        <option value="Hall Balaikota Semarang" {{ $plt->tempat == 'Hall Balaikota Semarang' ? 'selected' : '' }}>Hall Balaikota Semarang</option>
                                        <option value="Halaman Balaikota Semarang" {{ $plt->tempat == 'Halaman Balaikota Semarang' ? 'selected' : '' }}>Halaman Balaikota Semarang</option>
                                        <option value="Ruang Rapat Lantai 6 Siber Pungli" {{ $plt->tempat == 'Ruang Rapat Lantai 6 Siber Pungli' ? 'selected' : '' }}>Ruang Rapat Lantai 6 Siber Pungli</option>
                                    </select>
                                </div>
                                <div class="col-span-6 sm:col-span-3">
                                    <label for="deskripsi"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Deskripsi</label>
                                    <input type="text" name="deskripsi" placeholder="deskripsi" id="deskripsi" value="{{$plt->deskripsi}}"
                                        class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                </div>
                                <div class="col-span-full">
                                    <label class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" for="poster">Upload Poster:</label>
                                    <input class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400"
                                        aria-describedby="file_input_help" id="poster" name="poster" type="file" accept="image/*">
                                    @error('poster')
                                    <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400" role="alert">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-span-6 sm:col-full mt-4">
                                <button
                                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                    type="submit">Save all</button>
                            </div>
                        </div>
                </div>
            </div>
            </form>
        </div>
    </div>
@endsection