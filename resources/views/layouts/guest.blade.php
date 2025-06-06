<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Tracer Study UNP Kediri') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background-color: #f0f4f8;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23104e8b' fill-opacity='0.03'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9z'/%3E%3Cpath d='M6 5V0H5v5H0v1h5v94h1V6h94V5H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
                padding: 0;
            }
            .guest-container {
                width: 100%;
                max-width: 420px;
                margin: 0 auto;
                padding: 1rem;
            }
            .card-shadow {
                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
            }
            .guest-gradient {
                background: linear-gradient(135deg, #004080 0%, #0067b3 100%);
            }
            .input-focus:focus {
                border-color: #0067b3;
                box-shadow: 0 0 0 3px rgba(0, 103, 179, 0.15);
            }
        </style>
    </head>
    <body>
        <div class="guest-container">
            <div class="bg-white rounded-xl card-shadow overflow-hidden">
                <div class="guest-gradient p-6 text-white text-center">
                    <div class="flex justify-center">
                        <a href="/">
                            <img src="{{ asset('images/logo-unp.png') }}" alt="Logo UNP Kediri" class="h-16 drop-shadow-lg">
                        </a>
                    </div>
                    <h1 class="text-xl font-bold mt-3">Sistem Tracer Study</h1>
                    <p class="mt-1 text-blue-100 text-sm">Universitas Nusantara PGRI Kediri</p>
                </div>
                
                <div class="p-6">
                    {{ $slot }}
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Sistem Tracer Study - UNP Kediri</p>
            </div>
        </div>
    </body>
</html>