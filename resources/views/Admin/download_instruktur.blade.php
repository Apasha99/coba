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
            background-color: #ffffff; /* Warna latar belakang putih */
        }
        h1, h2, h3, h4, h6 {
            text-align: center;
            color: #0d47a1;
        }
        h5{
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
            padding: 2px;
            text-align: left;
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
            margin-bottom: 10px; /* Jarak dari bawah tombol */
        }
        .header-info {
            flex: 1;
            padding: 20px;
        }
        
    </style>

</head>
<body>
    <div class="kop-surat" style="display: flex;">
        <div style="flex: 1;" class="p-4 w-3/4">
            <h2>KEMENTERIAN KOMUNIKASI DAN INFORMATIKA REPUBLIK INDONESIA</h2>
            <h3>DIREKTORAT JENDERAL PENYELENGGARAAN POS DAN INFORMATIKA</h3>
            <p class="alamat" style="text-align: center;">
                Sekayu, Kec. Semarang Tengah, Kota Semarang, Jawa Tengah 50132<br>
                Tel/Fax. (024)3549446<br>
                diskominfo@semarangkota.go.id
            </p>
            <hr style="border-top: 4px solid black; margin-top: 20px; margin-bottom: 2px;">
            <hr style="border-top: 2px solid black; margin-top: 2px; margin-bottom: 2px;">
        </div>
    </div>
    <div class="container-lg">
        <div class="mb-4 col-span-full xl:mb-2">
            <h5>Daftar Akun Instruktur Pelatihan</h5>
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
                        Nama
                    </th>
                    <th scope="col"
                        class="p-2 text-s font-large tracking-wider text-center">
                        Bidang
                    </th>
                    <th scope="col"
                        class="p-2 text-s font-large tracking-wider text-center">
                        Username
                    </th>
                    <th scope="col"
                        class="p-2 text-s font-large tracking-wider text-center">
                        Email
                    </th>
                    <th scope="col"
                        class="p-2 text-s font-large tracking-wider text-center">
                        Password
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">
                @foreach ($instruktur as $pst)
                <tr>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">
                        <span class="font-semibold">{{$pst->instruktur_id}}</span>
                    </td>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">
                        {{$pst->nama}}
                    </td>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">
                        {{$pst->bidang}}
                    </td>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">
                        {{$pst->username}}
                    </td>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">
                        {{$pst->email}}
                    </td>
                    <td class="p-2 text-sm text-center font-normal text-gray-900 whitespace-nowrap">
                        {{$pst->password_awal}}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    
    
</body>
</html>
