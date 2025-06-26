@extends('layouts.app')
@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">Login</div>
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">@csrf
                    <div class="mb-3"><label for="email" class="form-label">E-Mail Adresse</label><input id="email" type="email" class="form-control" name="email" required autofocus></div>
                    <div class="mb-3"><label for="password" class="form-label">Passwort</label><input id="password" type="password" class="form-control" name="password" required></div>
                    <div class="mb-3 form-check"><input type="checkbox" class="form-check-input" id="remember" name="remember"><label class="form-check-label" for="remember">Angemeldet bleiben</label></div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection