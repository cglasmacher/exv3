@extends('layouts.app')

@section('content')
<div class="text-center mb-5">
    <h1 class="display-4 fw-bold">Vergleichen Sie Versanddienstleister im Handumdrehen</h1>
    <p class="lead text-secondary">Wählen Sie Ihr Paket, sehen Sie Preise und sparen Sie Zeit und Geld.</p>
</div>
<div class="row justify-content-center">
    <div class="col-lg-8">
        <form action="{{ route('quote.requests.store') }}" method="POST" class="border p-4 rounded bg-white shadow">
            @csrf
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="sender_country" class="form-label">Absenderland</label>
                    <input name="sender_country" id="sender_country" type="text" class="form-control" value="DE" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="recipient_country" class="form-label">Empfängerland</label>
                    <input name="recipient_country" id="recipient_country" type="text" class="form-control" value="DE" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="sender_postcode" class="form-label">Absender PLZ</label>
                    <input name="sender_postcode" id="sender_postcode" type="text" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="sender_city" class="form-label">Absender Stadt</label>
                    <input name="sender_city" id="sender_city" type="text" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="recipient_postcode" class="form-label">Empfänger PLZ</label>
                    <input name="recipient_postcode" id="recipient_postcode" type="text" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="recipient_city" class="form-label">Empfänger Stadt</label>
                    <input name="recipient_city" id="recipient_city" type="text" class="form-control" required>
                </div>
            </div>
            <hr>
            <h5>Packstücke</h5>
            <div id="items-container">
                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label">Typ</label>
                        <select name="items[0][item_type]" class="form-select" required>
                            <option value="package">Paket</option>
                            <option value="pallet">Palette</option>
                            <option value="document">Dokument</option>
                        </select>
                    </div>
                    <div class="col-md-2"><label class="form-label">Gewicht (kg)</label><input name="items[0][weight]" type="number" step="0.01" class="form-control" required></div>
                    <div class="col-md-2"><label class="form-label">Länge (cm)</label><input name="items[0][length]" type="number" step="0.1" class="form-control" required></div>
                    <div class="col-md-2"><label class="form-label">Breite (cm)</label><input name="items[0][width]" type="number" step="0.1" class="form-control" required></div>
                    <div class="col-md-2"><label class="form-label">Höhe (cm)</label><input name="items[0][height]" type="number" step="0.1" class="form-control" required></div>
                </div>
            </div>
            <button type="button" id="add-item" class="btn btn-outline-secondary mb-3">+ Weiteres Packstück</button>
            <button type="submit" class="btn btn-primary w-100">Jetzt vergleichen</button>
        </form>
        <template id="item-template">
            <div class="row g-3 mb-3">
                <div class="col-md-4"><label class="form-label">Typ</label><select name="items[__INDEX__][item_type]" class="form-select" required><option value="package">Paket</option><option value="pallet">Palette</option><option value="document">Dokument</option></select></div>
                <div class="col-md-2"><label class="form-label">Gewicht (kg)</label><input name="items[__INDEX__][weight]" type="number" step="0.01" class="form-control" required></div>
                <div class="col-md-2"><label class="form-label">Länge (cm)</label><input name="items[__INDEX__][length]" type="number" step="0.1" class="form-control" required></div>
                <div class="col-md-2"><label class="form-label">Breite (cm)</label><input name="items[__INDEX__][width]" type="number" step="0.1" class="form-control" required></div>
                <div class="col-md-2"><label class="form-label">Höhe (cm)</label><input name="items[__INDEX__][height]" type="number" step="0.1" class="form-control" required></div>
            </div>
        </template>
    </div>
</div>

@endsection
