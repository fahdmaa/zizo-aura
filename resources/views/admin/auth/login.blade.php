<!DOCTYPE html>
<html lang="fr" class="h-full bg-[#faf9f6]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Administration · Zizo Aura</title>
    
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
        <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-pink-50 text-pink-600 text-2xl font-black mb-4 shadow-sm">
            ZA
        </div>
        <h1 class="text-2xl md:text-3xl font-black text-zinc-900 tracking-tight">zizo aura</h1>
        <p class="text-xs font-bold text-pink-600 uppercase tracking-widest mt-1">Espace d'Administration</p>
    </div>

    <!-- Login Card -->
    <div class="bg-white rounded-3xl shadow-xl shadow-zinc-200/50 border border-zinc-200/80 p-8 md:p-10">
        <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-5">
            @csrf
            <div>
                <label class="block text-xs font-bold text-zinc-700 mb-2">Mot de passe administrateur</label>
                <div class="relative">
                    <input type="password" name="password" id="admin-password-input" autofocus required
                        class="w-full bg-zinc-50 border border-zinc-200 rounded-2xl px-4 py-3 text-sm font-medium text-zinc-800 focus:outline-none focus:border-pink-500 focus:bg-white transition @error('password') border-red-400 bg-red-50/50 @enderror"
                        placeholder="••••••••••••">
                    <button type="button" onclick="const i = document.getElementById('admin-password-input'); i.type = i.type === 'password' ? 'text' : 'password';" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-zinc-400 hover:text-zinc-700 p-1 cursor-pointer">
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
                class="w-full py-3.5 px-4 rounded-2xl text-xs font-black text-white transition shadow-lg shadow-pink-600/25 flex items-center justify-center gap-2 cursor-pointer"
                style="background: #ff1b7a;">
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
