@extends('admin.layout.layout')

@section('content')
    <div class="col-span-full xl:col-auto">
    <div class="m-auto">
        <a href="" class="mr-auto text-white bg-green-400 hover:bg-green-500 focus:ring-4 focus:ring-green-300 font-medium rounded-lg text-sm px-5 py-2.5 mr-2 mb-2 dark:bg-green-400 dark:hover:bg-green-500 focus:outline-none dark:focus:ring-green-600" type="button">
            + Tambah Pelatihan
        </a>
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
    



    
@endsection






