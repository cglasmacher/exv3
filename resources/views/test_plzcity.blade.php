<!-- resources/views/test_plzcity.blade.php -->
@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <h2 class="mb-4">PLZ-Stadt-Viewer</h2>
        <form id="test-plzcity-form" class="border p-4 rounded bg-white shadow-sm">
            @csrf
            <div class="mb-3">
                <label for="viewer_country" class="form-label">Land (ISO-Code)</label>
                <select id="viewer_country" class="form-select" required>
                    @foreach(\App\Models\Country::orderBy('name')->get() as $country)
                        <option value="{{ $country->iso_code }}">{{ $country->name }} ({{ $country->iso_code }})</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="viewer_postcode" class="form-label">Postleitzahl</label>
                <input id="viewer_postcode" type="text" class="form-control" placeholder="z.B. 10115" required>
            </div>
            <div class="mb-3">
                <label for="viewer_city" class="form-label">Stadt</label>
                <input id="viewer_city" type="text" class="form-control" readonly>
            </div>
            <button type="button" id="viewer_fetch" class="btn btn-primary w-100">Stadt abrufen</button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('viewer_fetch').addEventListener('click', async () => {
        const country = document.getElementById('viewer_country').value;
        const postalcode = document.getElementById('viewer_postcode').value.trim();
        const cityEl = document.getElementById('viewer_city');

        if (postalcode.length < 3) {
            alert('Bitte gib eine gültige Postleitzahl ein.');
            return;
        }

        try {
            const response = await fetch(`/api/locations/cities?country=${country}&postalcode=${postalcode}`);
            if (!response.ok) throw new Error('Fehler bei der Netzwerkverbindung');
            const cities = await response.json();
            if (cities.length > 0) {
                cityEl.value = cities[0];
            } else {
                cityEl.value = '';
                alert('Keine Stadt gefunden.');
            }
        } catch (error) {
            console.error(error);
            alert('Fehler beim Abrufen der Stadt.');
        }
    });
</script>
@endpush
