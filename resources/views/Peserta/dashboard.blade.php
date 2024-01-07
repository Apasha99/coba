@extends('peserta.layout.layout')

@section('content')
    <div class="flex justify-between items-center mb-4 col-span-full xl:mb-2">
        <h1 class="text-xl font-semibold text-gray-900 sm:text-2xl dark:text-white">Welcome, {{ $peserta->nama }}!</h1>

        <div class="relative lock mt-2 w-50 h-50 mb-2 mr-2 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <button type="button" data-modal-toggle="join-pelatihan-modal">
                <div class="flex items-center p-2 sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4">
                    <div>
                        <div class="flex items-center p-2">
                            <svg class="w-6 h-6 text-gray-800 dark:text-white mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 19">
                                <path d="M14.5 0A3.987 3.987 0 0 0 11 2.1a4.977 4.977 0 0 1 3.9 5.858A3.989 3.989 0 0 0 14.5 0ZM9 13h2a4 4 0 0 1 4 4v2H5v-2a4 4 0 0 1 4-4Z"/>
                                <path d="M5 19h10v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2ZM5 7a5.008 5.008 0 0 1 4-4.9 3.988 3.988 0 1 0-3.9 5.859A4.974 4.974 0 0 1 5 7Zm5 3a3 3 0 1 0 0-6 3 3 0 0 0 0 6Zm5-1h-.424a5.016 5.016 0 0 1-1.942 2.232A6.007 6.007 0 0 1 17 17h2a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5ZM5.424 9H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h2a6.007 6.007 0 0 1 4.366-5.768A5.016 5.016 0 0 1 5.424 9Z"/>
                            </svg>
                            <h3 class="mb-1 text-l font-bold text-gray-900 dark:text-white">Gabung Pelatihan</h3>
                        </div>
                    </div>
                </div>
            </button>
        </div>
    </div>

    
    <div class="flex flex-wrap ">
        <div class="relative lock mt-2 w-60 h-50 mb-4 mr-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800">
            @foreach($pelatihan as $plt)
            <a href="{{route('peserta.viewDetailPelatihan', $plt->plt_kode)}}" >
                <img src="{{ $plt->getPosterURL() }}" alt="poster pelatihan" class="w-full mb-2 h-40 object-cover rounded-t-lg" />
                <div class="items-center p-2 sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4">
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
    </div>

    <div class="fixed left-0 right-0 z-50 items-center justify-center hidden top-8 md:inset-0 sm:h-50"
        id="join-pelatihan-modal">
        <div class="relative w-50 h-50 max-w-2xl px-4 md:h-50">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-800 overflow">
                <!-- Modal header -->
                <div class="flex items-start justify-between p-5 border-b rounded-t dark:border-gray-700">
                    <h3 class="text-xl font-semibold dark:text-white">
                        Gabung Pelatihan
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-700 dark:hover:text-white"
                        data-modal-toggle="join-pelatihan-modal">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="p-6 space-y-6 overflow-y-auto">
                    <form action="{{route('peserta.joinPelatihan')}}" method="POST" enctype="multipart/form-data" >
                    @csrf
                        <div class="grid grid-cols-6 gap-6">
                            <div class="col-span-6 sm:col-span-6">
                                <label for="kode"
                                    class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kode</label>
                                <input type="text" name="kode" placeholder="Kode" id="kode"
                                    class="shadow-sm bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                    required>
                                @error('kode')
                                <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-red-100 dark:bg-gray-800 dark:text-red-400" role="alert">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <!-- Tombol untuk menyimpan data -->
                            <div class="col-span-full">
                                <button type="submit"
                                    class="text-white bg-primary-700 hover:bg-primary-800 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                                    data-modal-toggle="join-pelatihan-modal">
                                    Gabung Pelatihan
                                </button>
                            </div>
                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>

@endsection

