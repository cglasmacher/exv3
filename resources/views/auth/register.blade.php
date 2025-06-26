@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">Register</div>
            <div class="card-body">
                <form method="POST" action="{{ route('register') }}">@csrf
                    <div class="mb-3"><label for="name" class="form-label">Name</label><input id="name" type="text" class="form-control" name="name" required></div>
                    <div class="mb-3"><label for="email" class="form-label">E-Mail Adresse</label><input id="email" type="email" class="form-control" name="email" required></div>
                    <div class="mb-3"><label for="password" class="form-label">Passwort</label><input id="password" type="password" class="form-control" name="password" required></div>
                    <div class="mb-3"><label for="password_confirmation" class="form-label">Passwort bestätigen</label><input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required></div>
                    <button type="submit" class="btn btn-primary w-100">Registrieren</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection