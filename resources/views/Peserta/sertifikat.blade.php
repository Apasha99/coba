<html>
    <head>
        <style type='text/css'>
            body, html {
                margin: 0;
                padding: 0;
            }
            body {
                color: black;
                display: table;
                font-family: Georgia, serif;
                font-size: 24px;
                text-align: center;
            }
            .container {
                border: 20px solid tan;
                width: 755px;
                height: 563px;
                margin: auto;
                vertical-align: middle;
            }
            .content {
                position: absolute;
                left: 180px;
                top: 80px;
            }
            .logo {
                color: tan;
            }
            .marquee {
                color: tan;
                font-size: 48px;
                margin: 20px;
            }
            .assignment {
                margin: 20px;
                font-size: 20px;
            }
            .text-medium {
                margin-bottom: 10px;
                font-size: 16px;
            }
            .text-small {
                font-size: 14px;
            }
            .person {
                border-bottom: 2px solid black;
                font-size: 32px;
                font-style: italic;
                margin: 20px auto;
                width: 400px;
            }
            .signature {
                border-bottom: 2px solid black;
                font-size: 32px;
                font-style: italic;
                margin: 20px auto;
                width: 250px;
            }
            .signature2 {
                position: absolute;
                margin: 0;
                top: 320px;
                left: 300px;
                max-width: 180px;
            }
            .reason {
                margin-bottom: 5px;
                font-size: 20px;
            }
            .event {
                margin-bottom: 50px;
                font-size: 20px;
                font-weight: bold;
            }
            .logo2 {
                position: absolute;
                top: 14px;
                left: 14px;
                margin: 15px; 
                max-width: 36px; 
            }
            .logo3 {
                position: absolute;
                top: 180px;
                left: 280px;
                max-width: 200px;
                opacity: 0.15;
            }
            .text2 {
                position: absolute;
                top: 0;
                left: 60px; 
                margin-left: 15px;
                margin-top: 32px;
                font-size: 14px; 
                font-weight: bold;
            }
            .text3 {
                position: absolute;
                top: 0;
                left: 60px; 
                margin-left: 15px;
                margin-top: 52px;
                font-size: 14px; 
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <img class="logo2" src="https://upload.wikimedia.org/wikipedia/commons/f/f2/Lambang_Kota_Semarang.png" alt="logo">
            <p class="text2">Dinas Komunikasi Informatika Statistik</p>
            <p class="text3">dan Persandian Kota Semarang</p>
            <img class="logo3" src="https://upload.wikimedia.org/wikipedia/commons/f/f2/Lambang_Kota_Semarang.png" alt="logo">
            <div class="content">
            <div class="marquee">
               Sertifikat
            </div>

            <div class="assignment">
                diberikan kepada
            </div>

            <div class="person">
                Joe Nathan
            </div>

            <div class="reason">
                Atas partisipasinya sebagai peserta dalam
            </div>

            <div class="event">
                Pelatihan Cybersecurity
            </div>

            <div class="text-medium">
                Semarang, {{ now()->format('j M Y') }}
            </div>

            <br><br>
            <!-- <img class="signature2" src="{{ asset('storage/ttddhiya.png') }}" alt="Deskripsi Gambar"> -->
            <div class="signature">
            </div>
            <div class="text-medium">
                Sucahyo Kuswirantomo, S.H., S.Sos., M.H.
            </div>
            <div class="text-small">
                Kepala Bidang Pengembangan Komunikasi Publik
            </div>
            </div>
        </div>
    </body>
</html>