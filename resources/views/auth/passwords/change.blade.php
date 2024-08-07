@extends('layouts.login')

@section('content')
<div class="container">
    <h1>Cambiar Contraseña</h1>
    <form method="POST" action="{{ route('password.change.submit') }}">
        @csrf

        <div class="form-group">
            <label for="new_password">Nueva Contraseña</label>
            <input id="new_password" type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" required>
            @error('new_password')
                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
        </div>

        <div class="form-group">
            <label for="new_password_confirmation">Confirmar Nueva Contraseña</label>
            <input id="new_password_confirmation" type="password" class="form-control" name="new_password_confirmation" required>
        </div>

        <button type="submit" class="btn btn-primary">Cambiar Contraseña</button>
    </form>
</div>
@endsection
