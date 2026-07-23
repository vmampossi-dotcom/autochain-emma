<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion MetaMask</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-950 text-white">
    <div class="mx-auto flex min-h-screen max-w-2xl items-center justify-center px-6">
        <div class="w-full rounded-3xl border border-slate-800 bg-slate-900 p-8 shadow-2xl">
            <h1 class="text-2xl font-semibold">Connexion AutoChain avec MetaMask</h1>
            <p class="mt-3 text-sm text-slate-400">Connectez votre portefeuille pour accéder à votre tableau de bord.</p>

            <button id="connect-wallet" class="mt-6 rounded-2xl bg-indigo-600 px-4 py-3 font-semibold text-white">
                Connecter MetaMask
            </button>

            <p id="wallet-status" class="mt-4 text-sm text-slate-400">Aucun portefeuille connecté.</p>
        </div>
    </div>

    <script>
        async function connectWallet() {
            if (!window.ethereum) {
                alert('MetaMask n\'est pas installé.');
                return;
            }

            const accounts = await window.ethereum.request({ method: 'eth_requestAccounts' });
            const address = accounts[0];
            const response = await fetch('/wallet/nonce');
            const { nonce } = await response.json();
            const signature = await window.ethereum.request({
                method: 'personal_sign',
                params: [nonce, address],
            });

            const loginResponse = await fetch('/wallet/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({ address, signature, nonce })
            });

            if (!loginResponse.ok) {
                const errorData = await loginResponse.json().catch(() => null);
                const message = errorData?.message || 'Erreur de connexion, réessayez.';
                alert(message);
                return;
            }

            const data = await loginResponse.json();
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        }

        document.getElementById('connect-wallet').addEventListener('click', connectWallet);
    </script>
</body>
</html>
