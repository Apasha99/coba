<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daftar Rekap Test {{$test->nama}}</title>
    <!-- Bootstrap CSS (jika menggunakan framework Bootstrap) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- CSS styling -->
    <style>
        /* CSS style untuk membuat tampilan tabel */
        @page {
            size: A4 landscape;
            margin: 0;
        }
        body {
            margin: 20px;
        }
        h1, h2, h3, h4, h5, h6 {
            text-align: center;
        }
        table {
            width: 100%;
            font-size: 12px;
            border-collapse: collapse;
            margin-bottom: 1px; /* Jarak antara tabel dan tombol */
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 4px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .container-lg {
            margin-top: 20px; /* Jarak atas container */
        }
        .btn-print {
            float: right; /* Tombol Cetak di sebelah kanan */
            margin-bottom: 10px; /* Jarak dari bawah tombol */
        }
    </style>
</head>
<body>
    <div class="container-lg">
        <div class="mb-4 col-span-full xl:mb-2">
            <h2>Daftar Rekap Test {{$test->nama}} dalam Pelatihan {{$pelatihan->nama}}</h2>
            <h2>Dinas Kominfo Kota Semarang</h2>
        </div>

        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col"
                        class="p-4 text-xs font-large tracking-wider text-left text-gray-500 uppercase dark:text-white">
                        ID
                    </th>
                    <th scope="col"
                        class="p-4 text-xs font-large tracking-wider text-left text-gray-500 uppercase dark:text-white">
                        Nama Peserta
                    </th>
                    <th scope="col"
                        class="p-4 text-xs font-large tracking-wider text-left text-gray-500 uppercase dark:text-white">
                        KKM
                    </th>
                    <th scope="col"
                        class="p-4 text-xs font-large tracking-wider text-left text-gray-500 uppercase dark:text-white">
                        Nilai
                    </th>
                    <th scope="col"
                        class="p-4 text-xs font-large tracking-wider text-left text-gray-500 uppercase dark:text-white">
                        Jumlah Attempt
                    </th>
                    <th scope="col"
                        class="p-4 text-xs font-large tracking-wider text-left text-gray-500 uppercase dark:text-white">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800">
                @foreach ($highestScores as $nilai)
                <tr>
                    <td class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap dark:text-white">
                        <span class="font-semibold">{{$nilai->user_id}}</span>
                    </td>
                    <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                        {{$nilai->nama}}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                        {{$test->kkm}}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                        {{$nilai->max_totalnilai}}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                        {{$nilai->jumlah_attempt}}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                        @if ($hitungnilai >= $test->kkm)
                            <div class="mt-2 p-2 bg-green-200 rounded-lg">
                                <p class="text-center text-sm font-semibold text-green-800">
                                    Passed
                                </p>
                            </div>
                        @else
                            <div class="mt-2 p-2 bg-red-200 rounded-lg">
                                <p class="text-center text-sm font-semibold text-red-800">
                                    Failed
                                </p>
                            </div>
                        @endif                                       
                    </td>
                </tr>
                @endforeach
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td colspan="5" class="text-center p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">Total Nilai</td>
                    <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $hitungnilai }}</td>
                </tr>
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td colspan="5" class="text-center p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">Total Peserta</td>
                    <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $totalpeserta }}</td>
                </tr>
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td colspan="5" class="text-center p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">Peserta yang Mengerjakan Test</td>
                    <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">{{ $hitungpeserta }}</td>
                </tr>
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td colspan="5" class="text-center p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">Rata-rata</td>
                    <td class="p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        @if ($hitungpeserta > 0)
                            {{ $hitungnilai / $hitungpeserta }}
                        @else
                            0
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

</body>
</html>
