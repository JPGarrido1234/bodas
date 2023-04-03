@extends('layouts.app')
@section('titulo', 'Acceder')
@section('content')
<div class="container mt-4">
    <div class="row">
        @if(session()->has('success'))
        <div class="col-12">
            <div class="alert alert-success">
                Hemos recibido tu solicitud correctamente. En breves se pondrán en contacto contigo a través de los medios facilitados.
            </div>
        </div>
        @endif
        <div class="col-md-6 mt-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Acceder
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="row mb-3">
                            {{-- <div class="col-md-12">
                                <label for="username" class="col-form-label text-md-end">Usuario</label>
                                <input id="username" type="text" class="form-control @error('username') is-invalid @enderror" name="username" value="{{ old('username') }}" autofocus required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div> --}}
                            <div class="col-md-12">
                                <label for="email" class="col-form-label text-md-end">Correo electrónico</label>
                                <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" autofocus required>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="col-12 mt-2">
                                <label for="password" class="col-form-label text-md-end">Contraseña</label>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                            </div>
                            <div class="col-12 mt-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="remember">
                                        Recuérdame
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-0">
                            <div class="col-md-12">
                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        ¿Has olvidado la contraseña?
                                    </a>
                                @endif
                                <button type="submit" class="btn btn-primary float-end">
                                    Iniciar sesión
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-6 mt-2">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    Solicitar información
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('request-info') }}">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-12 mb-2">
                                <label for="name" class="col-form-label text-md-end">Nombre completo *</label>
                                <input id="name" type="text" class="form-control" name="name" required>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="tlf" class="col-form-label text-md-end">Teléfono *</label>
                                <input id="tlf" type="text" class="form-control" name="tlf" value="{{ old('tlf') }}" required>
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="email" class="col-form-label text-md-end">Correo electrónico *</label>
                                <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email">
                            </div>
                            <div class="col-md-12 mb-2">
                                <label for="msg" class="col-form-label text-md-end">Mensaje adicional (opcional)</label>
                                <textarea name="msg" id="msg" rows="5" class="form-control"></textarea>
                            </div>
                            <div class="col-md-12 mt-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" required name="privacidad" id="privacidad" {{ old('privacidad') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="privacidad">
                                        He leído y acepto la <a href="https://bodegascampos.com/politica-de-privacidad/" target="_blank" class="btn-link">política de privacidad y aviso legal</a>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <button class="btn btn-primary float-end mt-3" type="submit">Solicitar información</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
