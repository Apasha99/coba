@extends('admin.layout.layout')

@section('content')
    <div class="top-0 mb-4 col-span-full xl:mb-2 relative">
        <img class="mb-4 rounded-lg w-full h-40 sm:mb-0 xl:mb-4 2xl:mb-0" src="{{ asset('image/hi2.png') }}">
        <h1 class="absolute top-12 left-56 right-0 bottom-0 flex text-xl font-bold text-gray-900 sm:text-2xl dark:text-blue-900">Halo, {{ Illuminate\Support\Str::limit($admin->nama, 10, '...') }}!</h1>
        <p class="absolute top-20 left-56 right-0 bottom-0 flex text-sm font-semibold text-gray-900 sm:text-l dark:text-blue-900">Selamat bekerja!</p>
    </div>
    <div class="w-full h-full border border-gray-200 dark:bg-gray-800 dark:border-gray-800 rounded-xl">
        <div class="mt-4 ml-4">
            <h1 class="text-sm font-semibold text-gray-900 sm:text-lg dark:text-white">Overview</h1>
        </div>
        <div class="p-4 grid grid-cols-4 gap-4">
            <div class="items-center sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4 grid grid-cols-5 block max-w-full sm:max-w-sm p-2 bg-indigo-400 border border-gray-200 rounded-lg shadow dark:bg-indigo-900 dark:border-gray-700">
                <div class="col-span-2">
                    <img class="w-32 h-full" src="{{ asset('image/laptop.png') }}">
                </div>
                <div class="p-1 mt-2 col-span-3 ">
                    <p class="text-xl font-bold text-white sm:text-4xl dark:text-gray-100">
                        {{$jmlPltAktif}}
                    </p>
                    <p class="text-sm font-regular text-white sm:text-xs dark:text-gray-100">
                        Pelatihan Aktif
                    </p>
                </div>
            </div>

            <div class="items-center sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4 grid grid-cols-5 block max-w-full sm:max-w-sm p-2 bg-purple-400 border border-gray-200 rounded-lg shadow dark:bg-purple-900 dark:border-gray-700">
                <div class="col-span-2">
                    <img class="w-32 h-full" src="{{ asset('image/buku.png') }}">
                </div>
                <div class="p-1 mt-2 col-span-3">
                    <p class="text-xl font-bold text-white sm:text-4xl dark:text-gray-100">
                        {{$jmlPltSelesai}}
                    </p>
                    <p class="text-sm font-regular text-white sm:text-xs dark:text-gray-100">
                        Pelatihan Selesai
                    </p>
                </div>
            </div>

            <div class="items-center sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4 grid grid-cols-5 block max-w-full sm:max-w-sm p-2 bg-green-500 border border-gray-200 rounded-lg shadow dark:bg-green-900 dark:border-gray-700">
                <div class="col-span-2">
                    <img class="w-24 h-full" src="{{ asset('image/peserta.png') }}">
                </div>
                <div class="p-1 mt-2 col-span-3">
                    <p class="text-xl font-bold text-white sm:text-4xl dark:text-gray-100">
                        {{$jmlPeserta}}
                    </p>
                    <p class="text-sm font-regular text-white sm:text-xs dark:text-gray-100">
                        Peserta
                    </p>
                </div>
            </div>

            <div class="items-center grid grid-cols-5 block max-w-full sm:max-w-sm p-2 bg-yellow-400 border border-gray-200 rounded-lg shadow dark:bg-yellow-600 dark:border-gray-700">
                <div class="col-span-2">
                    <img class="w-full h-full" src="{{ asset('image/instruktur.png') }}">
                </div>
                <div class="p-1 mt-2 col-span-3">
                    <p class="text-xl font-bold text-white sm:text-4xl dark:text-white">
                        {{$jmlInstruktur}}
                    </p>
                    <p class="text-sm font-regular text-white sm:text-xs dark:text-white">
                        Instruktur
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="w-full h-full mt-4 border border-gray-200 dark:bg-gray-800 dark:border-gray-800 rounded-xl">
        <div class="mt-4 ml-4">
            <h1 class="text-sm font-semibold text-gray-900 sm:text-lg dark:text-white">Pelatihan Aktif</h1>
        </div>
        <div class="p-4 grid grid-cols-3 gap-4">
            @foreach ($pelatihan as $plt)
            <div class="relative lock w-70 h-50 mb-4 bg-white border border-gray-200 rounded-lg shadow-sm dark:border-gray-700 dark:bg-gray-800 pelatihan-row">
                <a href="{{ route('admin.viewDetailPelatihan', $plt->kode) }}" >
                    <img src="{{ $plt->getPosterURL() }}" alt="poster pelatihan" class="sm:w-60 md:w-80 mb-2 h-40 object-cover rounded-t-lg " />
                    <div class="items-center sm:flex xl:block 2xl:flex sm:space-x-4 xl:space-x-0 2xl:space-x-4">
                        <div>
                            <h3 class="mb-1 ml-2 mt-2 text-l font-bold text-gray-900 dark:text-white searchable">{{ Illuminate\Support\Str::limit($plt->nama, 50, '...') }}</h3>
                            <div class="mb-4 ml-2 mt-2 text-sm text-gray-500 dark:text-gray-400 searchable">
                                <p>
                                <span class="searchable bg-blue-200 text-blue-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-200">{{ $plt->kode }}</span>
                                    @if ($plt->status == 'On going')
                                    <span class="searchable bg-green-200 text-green-900 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-100">Aktif</span> 
                                    @elseif ($plt->status == 'Completed')
                                    <span class="searchable dark:bg-red-900 bg-red-300 text-red-900 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:text-red-200">Selesai</span>
                                    @elseif ($plt->status == 'Not started yet')
                                    <span class="searchable bg-gray-100 text-gray-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-gray-700 dark:text-gray-300">Belum mulai</span>
                                    @endif
                                    <span class="searchable bg-purple-300 px-2 py-1 text-xs font-medium text-purple-900 rounded ring-inset ring-purple-700/10 dark:bg-purple-900 dark:text-purple-200">{{ $plt->pesertaPelatihan }} peserta</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
@endsection






