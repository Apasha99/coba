<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Daftar Akun Instruktur Diskominfo</title>
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
            <h2>Daftar Akun Instruktur</h2>
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
                        Nama
                    </th>
                    <th scope="col"
                        class="p-4 text-xs font-large tracking-wider text-left text-gray-500 uppercase dark:text-white">
                        Bidang
                    </th>
                    <th scope="col"
                        class="p-4 text-xs font-large tracking-wider text-left text-gray-500 uppercase dark:text-white">
                        Username
                    </th>
                    <th scope="col"
                        class="p-4 text-xs font-large tracking-wider text-left text-gray-500 uppercase dark:text-white">
                        Email
                    </th>
                    <th scope="col"
                        class="p-4 text-xs font-large tracking-wider text-left text-gray-500 uppercase dark:text-white">
                        Password
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800">
                @foreach ($instruktur as $pst)
                <tr>
                    <td class="p-4 text-sm font-normal text-gray-900 whitespace-nowrap dark:text-white">
                        <span class="font-semibold">{{$pst->instruktur_id}}</span>
                    </td>
                    <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                        {{$pst->instruktur_nama}}
                    </td>
                    <td class="p-4 text-sm font-semibold text-gray-900 whitespace-nowrap dark:text-white">
                        {{$pst->bidang}}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                        {{$pst->username}}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                        {{$pst->email}}
                    </td>
                    <td class="p-4 text-sm font-normal text-gray-500 whitespace-nowrap dark:text-gray-400">
                        {{$pst->password_awal}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    
    
</body>
</html>
