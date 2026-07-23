@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-12 px-4">
    <div class="bg-gradient-to-br from-white/80 to-slate-50 dark:from-slate-900 dark:to-slate-800 shadow-lg rounded-xl overflow-hidden">
        <div class="p-8">
            <div class="flex items-start justify-between gap-6">
                <div>
                    <h1 class="text-3xl font-extrabold text-slate-900 dark:text-white">Preuve blockchain enregistrée</h1>
                    <p class="mt-1 text-sm text-slate-500 dark:text-slate-400">Preuve créée et liée au véhicule. Vous pouvez copier ou télécharger la preuve ci‑dessous.</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-slate-900 text-white rounded-md shadow">Retour</a>
                </div>
            </div>

            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 bg-white dark:bg-slate-900 rounded-lg border border-slate-100 dark:border-slate-700 p-4">
                    <h2 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Preuve (hash)</h2>
                    <div class="mt-3 flex items-start gap-3">
                        <pre id="proofValue" class="whitespace-pre-wrap break-words text-sm text-slate-800 dark:text-slate-100 bg-slate-50 dark:bg-slate-800 p-3 rounded-md flex-1">{{ $proof_hash }}</pre>
                        <div class="flex flex-col gap-2">
                            <button id="copyProof" class="px-3 py-2 bg-indigo-600 text-white rounded-md text-sm">Copier</button>
                            <a id="downloadProof" href="#" class="px-3 py-2 bg-white/80 border border-slate-200 rounded-md text-sm text-slate-700">Télécharger</a>
                        </div>
                    </div>
                    <p class="mt-2 text-xs text-slate-500">Conservez cette preuve pour vos enregistrements. Elle correspond à la transaction locale de preuve.</p>
                </div>

                <div class="bg-white dark:bg-slate-900 rounded-lg border border-slate-100 dark:border-slate-700 p-4">
                    <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Détails</h3>
                    <dl class="mt-3 text-sm text-slate-700 dark:text-slate-300 space-y-2">
                        @if($vehicle)
                        <div>
                            <dt class="text-xs text-slate-500">Véhicule</dt>
                            <dd class="font-medium">{{ $vehicle->model }} — {{ $vehicle->vin }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs text-slate-500">Propriétaire</dt>
                            <dd class="font-medium break-words">{{ $vehicle->owner_address }}</dd>
                        </div>
                        @else
                        <div>
                            <dt class="text-xs text-slate-500">Véhicule</dt>
                            <dd class="font-medium">Non renseigné</dd>
                        </div>
                        @endif
                        <div>
                            <dt class="text-xs text-slate-500">Date</dt>
                            <dd class="font-medium">{{ now()->format('d/m/Y H:i') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="/" class="text-sm text-slate-600 dark:text-slate-300">Accueil</a>
                <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-slate-900 text-white rounded-md">Retour au dashboard</a>
            </div>
        </div>
    </div>
</div>

<script>
    (function(){
        const copyBtn = document.getElementById('copyProof');
        const proofEl = document.getElementById('proofValue');
        const downloadLink = document.getElementById('downloadProof');

        if (copyBtn && proofEl) {
            copyBtn.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(proofEl.innerText.trim());
                    copyBtn.textContent = 'Copié';
                    setTimeout(() => copyBtn.textContent = 'Copier', 1500);
                } catch (e) {
                    alert('Impossible de copier. Sélectionnez manuellement le texte.');
                }
            });
        }

        if (downloadLink && proofEl) {
            const blob = new Blob([proofEl.innerText.trim()], { type: 'text/plain;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            downloadLink.setAttribute('href', url);
            downloadLink.setAttribute('download', 'proof.txt');
        }
    })();
</script>

@endsection
