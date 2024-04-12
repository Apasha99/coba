<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <title>Forget Password</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="flex items-center justify-center min-h-screen " style="background-image: url('{{ asset('image/bglogin.png') }}')">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-md p-8">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-blue-500 transform rotate-180" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                            clip-rule="evenodd"></path>
                    </svg>

                    <a class="text-s font-large text-blue-500 dark:text-blue-500" href="{{route('login')}}">Kembali</a>
                </div>
                <h2 class="text-2xl font-bold text-center mb-6">Forget Password</h2>
                <form action="{{ route('forgotpassword') }}" method="post"  enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2" for="email">Email Address</label>
                        <input class="appearance-none border rounded-lg w-full py-2 px-3 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" placeholder="Enter your email address">
                    </div>
                    <div class="flex items-center justify-center">
                        <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit" >Send Reset Link</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>