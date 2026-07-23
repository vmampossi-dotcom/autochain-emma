<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('MetaMask') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-2xl bg-white dark:bg-gray-800 shadow-sm p-6">
                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">Connecter MetaMask</h1>
                <div class="mt-4 space-y-3">
                    <button id="connectBtn" class="rounded-xl bg-indigo-600 px-4 py-2 text-white hover:bg-indigo-500">Connecter MetaMask</button>
                    <div class="mt-4 grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="flex flex-col gap-2 rounded-md border border-slate-200 bg-slate-50 p-3">
                            <div class="text-xs font-medium text-slate-500 uppercase">Compte</div>
                            <div class="flex items-center justify-between">
                                <div id="accountDisplay" class="font-mono text-sm text-slate-800 dark:text-slate-100">—</div>
                                <button id="copyAccount" class="ml-3 text-xs text-indigo-600 hover:underline">Copier</button>
                            </div>
                        </div>

                        <div class="flex flex-col gap-2 rounded-md border border-slate-200 bg-slate-50 p-3">
                            <div class="text-xs font-medium text-slate-500 uppercase">Réseau</div>
                            <div id="networkDisplay" class="text-sm text-slate-800 dark:text-slate-100">—</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const MetamaskConnector = {
            isInstalled() { return typeof window.ethereum !== 'undefined'; },
            async connect() { const acc = await window.ethereum.request({ method: 'eth_requestAccounts' }); return acc[0]; },
            async getChainId() { return await window.ethereum.request({ method: 'eth_chainId' }); },
            onAccountChanged(handler) { window.ethereum.on('accountsChanged', handler); },
            onChainChanged(handler) { window.ethereum.on('chainChanged', handler); },
        };

        document.addEventListener('DOMContentLoaded', () => {
            const button = document.getElementById('connectBtn');
            const accountDisplay = document.getElementById('accountDisplay');
            const networkDisplay = document.getElementById('networkDisplay');
            const copyBtn = document.getElementById('copyAccount');

            function truncate(addr) {
                if (!addr) return '—';
                return addr.slice(0, 6) + '…' + addr.slice(-4);
            }

            function getChainName(chainId) {
                const map = {
                    '0x1': 'Ethereum Mainnet',
                    '0x5': 'Goerli',
                    '0x89': 'Polygon',
                    '0x13881': 'Mumbai',
                    '0x4': 'Rinkeby',
                    '0x3': 'Ropsten'
                };
                return map[chainId] || chainId || '—';
            }

            async function updateUI(address, chainId) {
                accountDisplay.textContent = address ? truncate(address) : '—';
                accountDisplay.title = address || '';
                networkDisplay.textContent = getChainName(chainId);
            }

            if (copyBtn) copyBtn.addEventListener('click', () => {
                const full = accountDisplay.title;
                if (!full) return;
                navigator.clipboard.writeText(full).then(() => {
                    copyBtn.textContent = 'Copié';
                    setTimeout(() => copyBtn.textContent = 'Copier', 1500);
                });
            });

            if (!button) return;

            button.addEventListener('click', async () => {
                try {
                    if (!MetamaskConnector.isInstalled()) return alert('MetaMask non installé');
                    const account = await MetamaskConnector.connect();
                    const chain = await MetamaskConnector.getChainId();
                    updateUI(account, chain);
                    MetamaskConnector.onAccountChanged(accounts => updateUI(accounts[0] || null, null));
                    MetamaskConnector.onChainChanged(chainId => updateUI(null, chainId));
                } catch (e) {
                    alert(e.message);
                }
            });
        });
    </script>
</x-app-layout>
