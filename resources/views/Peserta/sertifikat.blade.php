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
                width: 1085px; 
                height: 753px; 
                margin: auto;
                vertical-align: middle;
            }
            .container2 {
                position: absolute;
                left: 180px;
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
                font-size: 68px;
                margin: 20px;
                
            }
            .assignment {
                margin: 20px;
                font-size: 26px;
            }
            .text-medium {
                margin-bottom: 10px;
                font-size: 20px;
            }
            .text-small {
                font-size: 20px;
            }
            .person {
                border-bottom: 2px solid black;
                font-size: 42px;
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
                font-size: 26px;
            }
            .event {
                margin-bottom: 50px;
                font-size: 28px;
                font-weight: bold;
            }
            .logo2 {
                position: absolute;
                top: 14px;
                left: 14px;
                margin: 15px; 
                max-width: 50px; 
            }
            .logo3 {
                position: absolute;
                left: 36px;
                top: 100px;
                margin: 20px; 
                max-width: 300px;
                opacity: 0.15;
            }
            .text2 {
                position: absolute;
                top: 0;
                left: 70px; 
                margin-left: 15px;
                margin-top: 32px;
                font-size: 20px; 
                font-weight: bold;
            }
            .text3 {
                position: absolute;
                top: 0;
                left: 70px; 
                margin-left: 15px;
                margin-top: 52px;
                font-size: 20px; 
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <img class="logo2" src="https://upload.wikimedia.org/wikipedia/commons/f/f2/Lambang_Kota_Semarang.png" alt="logo">
            <p class="text2">Dinas Komunikasi Informatika Statistik</p>
            <p class="text3">dan Persandian Kota Semarang</p>
            
            <div class="content">
            <div class="container2">
            <div class="marquee">
               Sertifikat
            </div>

            <div class="assignment">
                diberikan kepada
            </div>
            <img class="logo3" src="https://upload.wikimedia.org/wikipedia/commons/f/f2/Lambang_Kota_Semarang.png" alt="logo">

            <div class="person">
                {{ $peserta->nama }}
            </div>
            

            <div class="reason">
                Atas partisipasinya sebagai peserta dalam
            </div>
            
            <div class="event">
                {{ $pelatihan->nama }}
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
        </div>
    </body>
</html>