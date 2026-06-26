<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Embud Creative</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-950 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md px-6">

    <div class="text-center mb-8">
        <span class="text-5xl">🎵</span>
        <h1 class="text-3xl font-bold text-purple-400 mt-2">Embud Creative</h1>
        <p class="text-gray-400 text-sm mt-1">Buat akun baru</p>
    </div>

    <div class="bg-gray-900 border border-gray-700 rounded-2xl p-8">

        @if($errors->any())
        <div class="bg-red-900 border border-red-600 text-red-300 px-4 py-3 rounded-lg mb-4 text-sm">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}
        </div>
        @endif

        <form action="/register" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="text-gray-300 text-sm block mb-2">
                    <i class="fas fa-user mr-1"></i> Nama Lengkap
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-purple-500 focus:outline-none transition"
                       placeholder="Nama kamu">
            </div>

            <div>
                <label class="text-gray-300 text-sm block mb-2">
                    <i class="fas fa-envelope mr-1"></i> Email
                </label>
                <input type="email" name="email" value="{{ old('email') }}"
                       class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-purple-500 focus:outline-none transition"
                       placeholder="email@kamu.com">
            </div>

            <div>
                <label class="text-gray-300 text-sm block mb-2">
                    <i class="fas fa-lock mr-1"></i> Password
                </label>
                <input type="password" name="password"
                       class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-purple-500 focus:outline-none transition"
                       placeholder="Minimal 8 karakter">
            </div>

            <div>
                <label class="text-gray-300 text-sm block mb-2">
                    <i class="fas fa-lock mr-1"></i> Konfirmasi Password
                </label>
                <input type="password" name="password_confirmation"
                       class="w-full bg-gray-800 border border-gray-600 rounded-xl px-4 py-3 text-white placeholder-gray-500 focus:border-purple-500 focus:outline-none transition"
                       placeholder="Ulangi password">
            </div>

            <button type="submit"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-xl font-bold transition text-lg">
                <i class="fas fa-user-plus mr-2"></i>Daftar
            </button>
        </form>

        <p class="text-center text-gray-400 text-sm mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-purple-400 hover:underline font-medium">Masuk di sini!</a>
        </p>
    </div>
</div>
</body>
</html>