<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#faf9f6]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration · Zizo Aura</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon.png">
    <link rel="icon" type="image/x-icon" href="/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css'])
    
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #faf9f6;
            color: #0f172a;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 relative overflow-hidden">

<!-- Subtle Aesthetic Background Glows -->
<div class="absolute -top-32 -left-32 w-96 h-96 bg-pink-500/10 rounded-full blur-3xl pointer-events-none"></div>
<div class="absolute -bottom-32 -right-32 w-96 h-96 bg-pink-500/10 rounded-full blur-3xl pointer-events-none"></div>

<div class="w-full max-w-md relative z-10">
    <!-- Brand Header -->
    <div class="text-center mb-8">
        <a href="{{ route('home') }}" class="inline-block hover:opacity-85 transition-opacity mb-2 select-none" aria-label="zizo aura">
            <img src="/images/logo.png" alt="zizo aura - Beauty & Care" class="h-12 sm:h-14 w-auto object-contain mx-auto" />
        </a>
        <p class="text-[11px] font-bold text-pink-600 uppercase tracking-widest">Espace d'Administration</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white rounded-3xl shadow-xl shadow-zinc-200/50 border border-zinc-200/80 p-8 md:p-10">
        <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-zinc-700 mb-2">Mot de passe administrateur</label>
                <div class="relative">
                    <input type="password" name="password" id="admin-password-input" autofocus required
                        class="input-luxury w-full px-4 py-3.5 text-sm font-medium pr-12 @error('password') border-red-400 bg-red-50/50 @enderror"
                        placeholder="••••••••••••">
                    <button type="button" onclick="const i = document.getElementById('admin-password-input'); i.type = i.type === 'password' ? 'text' : 'password';" class="btn-circle-action absolute right-3 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-pink-600 w-8 h-8 cursor-pointer">
                        <i class="ti ti-eye text-base"></i>
                    </button>
                </div>
                @error('password')
                    <p class="mt-2 text-xs font-semibold text-red-500 flex items-center gap-1">
                        <i class="ti ti-alert-circle"></i>
                        <span>{{ $message }}</span>
                    </p>
                @enderror
            </div>

            <button type="submit"
                class="btn-pill-primary w-full py-4 text-xs font-extrabold uppercase tracking-wider">
                <span>Accéder au Tableau de Bord</span>
                <i class="ti ti-arrow-right text-sm"></i>
            </button>
        </form>
    </div>

    <!-- Bottom Notice -->
    <p class="text-center text-xs text-zinc-400 font-medium mt-6">
        Boutique officielle Zizo Aura Maroc · Accès réservé
    </p>
</div>

</body>
</html>
