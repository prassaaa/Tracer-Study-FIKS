<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Tracer Study FIKS UNP Kediri</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f0f4f8;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Cg fill-rule='evenodd'%3E%3Cg fill='%23104e8b' fill-opacity='0.03'%3E%3Cpath opacity='.5' d='M96 95h4v1h-4v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4h-9v4h-1v-4H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15v-9H0v-1h15V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h9V0h1v15h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9h4v1h-4v9zm-1 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm9-10v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-10 0v-9h-9v9h9zm-9-10h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9zm10 0h9v-9h-9v9z'/%3E%3Cpath d='M6 5V0H5v5H0v1h5v94h1V6h94V5H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
        .login-gradient {
            background: linear-gradient(135deg, #004080 0%, #0067b3 100%);
        }
        .input-focus:focus {
            border-color: #0067b3;
            box-shadow: 0 0 0 3px rgba(0, 103, 179, 0.15);
        }
        .btn-hover:hover {
            transform: translateY(-2px);
        }
        .card-shadow {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="container max-w-md">
        <div class="bg-white rounded-xl card-shadow overflow-hidden">
            <div class="login-gradient p-6 text-white text-center">
                <div class="flex justify-center">
                    <img src="{{ asset('images/logo-unp.png') }}" alt="Logo UNP Kediri" class="h-16 drop-shadow-lg">
                </div>
                <h1 class="text-xl font-bold mt-3">Sistem Tracer Study FIKS</h1>
                <p class="mt-1 text-blue-100 text-sm">Universitas Nusantara PGRI Kediri</p>
            </div>

            <div class="p-6">
                <h2 class="text-xl font-semibold text-center text-gray-800 mb-6">Masuk ke Akun Anda</h2>

                @if (session('status'))
                    <div class="bg-blue-50 border-l-4 border-blue-500 text-blue-700 p-4 mb-6 rounded-r-md" role="alert">
                        <p class="font-medium">{{ session('status') }}</p>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-5">
                        <label for="login_field" class="block text-sm font-medium text-gray-700 mb-1">Email atau NPM</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                                <i class="fas fa-user" id="login-icon"></i>
                            </div>
                            <input id="login_field" type="text" name="login_field" value="{{ old('login_field', old('nim')) }}" required autofocus
                                class="pl-10 w-full h-10 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all input-focus"
                                placeholder="Masukkan Email atau NPM">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">
                            <i class="fas fa-info-circle mr-1"></i>
                            Admin: gunakan email | Alumni: gunakan NPM
                        </p>
                        @error('login_field')
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        @error('nim')
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="mb-5">
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input id="password" type="password" name="password" required
                                class="pl-10 w-full h-10 border border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50 transition-all input-focus"
                                placeholder="••••••••">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <button type="button" id="togglePassword" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">
                                <i class="fas fa-exclamation-circle mr-1"></i>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center">
                            <input id="remember_me" type="checkbox" name="remember"
                                class="w-4 h-4 rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-200 focus:ring-opacity-50">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-700">Ingat Saya</label>
                        </div>

                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium transition-colors">Lupa Password?</a>
                        @endif
                    </div>

                    <div>
                        <button type="submit" class="w-full py-2.5 bg-blue-600 text-white rounded-md shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-300 btn-hover">
                            <i class="fas fa-sign-in-alt mr-2"></i> Masuk
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center">
                    <p class="text-sm text-gray-600">Belum memiliki akun? <a href="{{ route('register') }}" class="text-blue-600 hover:text-blue-800 font-medium transition-colors">Daftar</a></p>
                </div>
            </div>
        </div>

        <div class="mt-4 text-center">
            <p class="text-sm text-gray-500">&copy; {{ date('Y') }} Sistem Tracer Study FIKS - UNP Kediri</p>
        </div>
    </div>

    <script>
        // Toggle password visibility
        document.getElementById('togglePassword').addEventListener('click', function() {
            const password = document.getElementById('password');
            const icon = this.querySelector('i');

            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });

        // Dynamic icon change based on input type
        document.getElementById('login_field').addEventListener('input', function() {
            const loginIcon = document.getElementById('login-icon');
            const value = this.value;

            // Check if input looks like an email
            if (value.includes('@') && value.includes('.')) {
                loginIcon.classList.remove('fa-user', 'fa-id-card');
                loginIcon.classList.add('fa-envelope');
            } else if (value.length > 0) {
                // Looks like NPM
                loginIcon.classList.remove('fa-user', 'fa-envelope');
                loginIcon.classList.add('fa-id-card');
            } else {
                // Default state
                loginIcon.classList.remove('fa-envelope', 'fa-id-card');
                loginIcon.classList.add('fa-user');
            }
        });
    </script>
</body>
</html>
