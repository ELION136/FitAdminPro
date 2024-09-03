@extends('layouts.login')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Cambiar Contraseña</h4>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('password.change.submit') }}">
                            @csrf

                            <div class="mb-3">
                               <div class="form-group">
                                <input id="new_password" type="password"
                                    class="form-control @error('new_password') is-invalid @enderror" name="new_password"
                                    required placeholder=" ">
                                    <label for="new_password" class="form-label">Nueva Contraseña</label>
                                @error('new_password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                               </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-group">
                                <input id="new_password_confirmation" type="password" class="form-control"
                                    name="new_password_confirmation" required placeholder=" ">
                                <label for="new_password_confirmation" class="form-label">Confirmar Nueva Contraseña</label>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Cambiar Contraseña
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
