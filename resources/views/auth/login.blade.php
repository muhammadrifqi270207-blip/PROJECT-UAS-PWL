<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Embud Creative</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body class="bg-gray-950 min-h-screen flex items-center justify-center">

<div class="w-full max-w-md px-6">

    {{-- LOGO --}}
    <div class="text-center mb-8">
        <span class="text-5xl">🎵</span>
        <h1 class="text-3xl font-bold text-purple-400 mt-2">Embud Creative</h1>
        <p class="text-gray-400 text-sm mt-1">Masuk ke akun kamu</p>
    </div>

    {{-- CARD --}}
    <div class="bg-gray-900 border border-gray-700 rounded-2xl p-8">

        @if($errors->any())
        <div class="bg-red-900 border border-red-600 text-red-300 px-4 py-3 rounded-lg mb-4 text-sm">
            <i class="fas fa-exclamation-circle mr-2"></i>{{ $errors->first() }}
        </div>
        @endif

        <form action="/login" method="POST" class="space-y-5">
            @csrf

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
                       placeholder="••••••••">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="remember" id="remember" class="accent-purple-500">
                <label for="remember" class="text-gray-400 text-sm">Ingat saya</label>
            </div>

            <button type="submit"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 rounded-xl font-bold transition text-lg">
                <i class="fas fa-sign-in-alt mr-2"></i>Masuk
            </button>
        </form>

        <p class="text-center text-gray-400 text-sm mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-purple-400 hover:underline font-medium">Daftar sekarang!</a>
        </p>
    </div>
</div>
</body>
</html>