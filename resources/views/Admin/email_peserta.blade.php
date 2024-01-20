@component('mail::message')
    # {{$subject}}

    Terima kasih telah mendaftar. Berikut adalah informasi login Anda:

    - **Username:** {{ $username }}
    - **Password:** {{ $password }}
    - **Kode Pelatihan:** {{ $kode }}

    Silakan login di website kami http://127.0.0.1:8000/login.

    Terima kasih,
    Tim Kami
@endcomponent