<div class="mt-4 flex flex-wrap gap-3">
    <form method="POST" action="{{ url('/blockchain/ping') }}" class="blockchain-form" data-action="{{ url('/blockchain/register-vehicle') }}" data-vehicle-id="{{ $vehicle->id }}">
        @csrf
        <input type="hidden" name="vehicle_id" value="{{ $vehicle->id }}" />
        <button type="submit" class="rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white">Signer véhicule</button>
    </form>

    @if ($vehicle->maintenanceEntries->isNotEmpty())
        <form method="POST" action="{{ url('/blockchain/ping') }}" class="blockchain-form" data-action="{{ url('/blockchain/register-maintenance') }}" data-maintenance-id="{{ $vehicle->maintenanceEntries->first()->id }}">
            @csrf
            <input type="hidden" name="maintenance_id" value="{{ $vehicle->maintenanceEntries->first()->id }}" />
            <button type="submit" class="rounded-xl border border-indigo-200 bg-indigo-50 px-3 py-2 text-sm font-semibold text-indigo-700">Signer maintenance</button>
        </form>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const forms = document.querySelectorAll('.blockchain-form');
        forms.forEach(form => {
            form.addEventListener('submit', function (e) {
                e.preventDefault();

                const action = form.dataset.action || form.action;
                const formData = new FormData(form);

                // Prefer meta CSRF token, fallback to hidden _token input
                const meta = document.querySelector('meta[name="csrf-token"]');
                let token = meta ? meta.getAttribute('content') : null;
                if (!token) {
                    const hidden = form.querySelector('input[name="_token"]');
                    token = hidden ? hidden.value : '';
                }

                fetch(action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': token || '',
                        'Accept': 'application/json'
                    },
                    body: formData,
                    credentials: 'same-origin'
                }).then(async res => {
                    if (res.status === 419) {
                        // CSRF/session problem — prompt user to reload and re-authenticate
                        alert('Session expired or CSRF token missing. Reload the page and retry.');
                        window.location.reload();
                        return;
                    }

                    let dataText = await res.text();
                    try {
                        const json = JSON.parse(dataText);
                        if (json.proof_hash) {
                            // Redirect to professional result page with proof
                            const vid = form.dataset.vehicleId || form.querySelector('input[name="vehicle_id"]')?.value || '';
                            const url = `/blockchain/result?vehicle_id=${encodeURIComponent(vid)}&proof_hash=${encodeURIComponent(json.proof_hash)}`;
                            window.location.href = url;
                            return;
                        }

                        if (json.message) {
                            alert(json.message);
                        } else {
                            alert(JSON.stringify(json));
                        }
                    } catch (e) {
                        alert('Response: ' + dataText);
                    }
                }).catch(err => {
                    alert('Fetch error: ' + err.message);
                });
            });
        });
    });
</script>
