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
            margin: 50;
        }
        body {
            background-color: #ffffff; /* Warna latar belakang putih */
        }
        h1, h2, h3, h4, h6 {
            text-align: center;
            color: #0d47a1;
        }
        h5{
            text-align: center;
            font-size: 14px;
        }
        table {
            width: 100%;
            text-align: center;
            font-size: 16px;
            border-collapse: collapse;
            margin-bottom: 1px; /* Jarak antara tabel dan tombol */
        }
        table, th, td {
            text-align: center;
            border: 1px solid black;
        }
        th, td {
            padding: 5px;
            text-align: center;
        }
        th {
            background-color: #0d47a1; /* Warna latar belakang biru */
            color: #ffffff; /* Warna teks putih */
        }
        .container-lg {
            margin-top: 20px; /* Jarak atas container */
        }
        .btn-print {
            float: right; /* Tombol Cetak di sebelah kanan */
            margin-bottom: 5px; /* Jarak dari bawah tombol */
        }
        .header-info {
            flex: 1;
            padding: 10px;
        }
        
    </style>
</head>
<body>
    <div class="kop-surat" style="display: flex;">
        <div style="flex: 1;" class=" w-3/4">
            <h2>KEMENTERIAN KOMUNIKASI DAN INFORMATIKA REPUBLIK INDONESIA</h2>
            <h3>DIREKTORAT JENDERAL PENYELENGGARAAN POS DAN INFORMATIKA</h3>
            <p class="alamat" style="text-align: center;">
                Sekayu, Kec. Semarang Tengah, Kota Semarang, Jawa Tengah 50132<br>
                Tel/Fax. (024)3549446<br>
                diskominfo@semarangkota.go.id
            </p>
            <hr style="border-top: 4px solid black; margin-top: 20px; margin-bottom: 2px;">
            <hr style="border-top: 2px solid black; margin-top: 2px;">
        </div>
    </div>
    <div class="container-lg">
        <div class="mb-4 col-span-full xl:mb-2">
            <h3>Daftar Rekap Test {{$test->nama}} dalam Pelatihan {{$pelatihan->nama}}</h3>
        </div>

        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-600">
            <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                    <th scope="col"
                    class="p-2 text-s font-large tracking-wider text-center">
                        ID
                    </th>
                    <th scope="col"
                    class="p-2 text-s font-large tracking-wider text-center">
                        Nama Peserta
                    </th>
                    <th scope="col"
                    class="p-2 text-s font-large tracking-wider text-center">
                        KKM
                    </th>
                    <th scope="col"
                    class="p-2 text-s font-large tracking-wider text-center">
                        Nilai
                    </th>
                    <th scope="col"
                    class="p-2 text-s font-large tracking-wider text-center">
                        Jumlah Attempt
                    </th>
                    <th scope="col"
                    class="p-2 text-s font-large tracking-wider text-center">
                        Status
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800">
                @foreach ($highestScores as $nilai)
                <tr>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">
                        <span class="font-semibold">{{$nilai->user_id}}</span>
                    </td>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">
                        {{$nilai->nama}}
                    </td>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">
                        {{$test->kkm}}
                    </td>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">
                        {{$nilai->max_totalnilai}}
                    </td>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">
                        {{$nilai->jumlah_attempt}}
                    </td>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">
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
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">{{ $hitungnilai }}</td>
                </tr>
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td colspan="5" class="text-center p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">Total Peserta</td>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">{{ $totalpeserta }}</td>
                </tr>
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td colspan="5" class="text-center p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">Peserta yang Mengerjakan Test</td>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">{{ $hitungpeserta }}</td>
                </tr>
                <tr class="hover:bg-gray-100 dark:hover:bg-gray-700">
                    <td colspan="5" class="text-center p-4 text-base font-medium text-gray-900 whitespace-nowrap dark:text-white">Rata-rata</td>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">
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
