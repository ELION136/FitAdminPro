@extends('layouts.admin')
@section('content')
    <div class="position-relative mx-n4 mt-n4">
        <div class="profile-wid-bg profile-setting-img">
            <img src="{{ url('dist/assets/images/f6.jpg') }}" class="profile-wid-img" alt="">
            <div class="overlay-content">
                <div class="text-end p-3">
                    <div class="p-0 ms-auto rounded-circle profile-photo-edit">
                        <input id="profile-foreground-img-file-input" type="file"
                            class="profile-foreground-img-file-input">
                        <label for="profile-foreground-img-file-input" class="profile-photo-edit btn btn-light">
                            <i class="ri-image-edit-line align-bottom me-1"></i> Change Cover
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Page Header Close -->



    <div class="row">
        <div class="col-xxl-3">
            <div class="card mt-n5">
                <div class="card-body p-4">
                    <div class="text-center">
                        <div class="profile-user position-relative d-inline-block mx-auto  mb-4">
                            <img src="{{ $user->image ? asset('storage/' . $user->image) : asset('images/default-profile.png') }}"
                                class="rounded-circle avatar-xl img-thumbnail user-profile-image material-shadow"
                                alt="Foto de perfil">
                        </div>
                        <h5 class="fs-16 mb-1">{{ $user->nombreUsuario }}</h5>
                        <p class="text-muted mb-0">Administrador</p>
                    </div>
                </div>
            </div>
            <!--end card-->
        </div>

        <!--end col-->
        <div class="col-xxl-9">
            <div class="card mt-xxl-n5">
                <div class="card-header">
                    <ul class="nav nav-tabs-custom rounded card-header-tabs border-bottom-0" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-bs-toggle="tab" href="#personalDetails" role="tab">
                                <i class="fas fa-home"></i>Detallles Personales
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="card-body p-4">
                    <div class="tab-content">
                        <div class="tab-pane active" id="personalDetails" role="tabpanel">
                            <form action="{{ route('profile.update') }}" method="POST" id="updateProfileForm"
                                enctype="multipart/form-data">

                                @csrf
                                @method('PUT')


                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="firstname" class="form-label">Nombre de usuario</label>
                                            <input type="text" class="form-control" id="firstname" name="nombreUsuario"
                                                value="{{ old('nombreUsuario', $user->nombreUsuario) }}">
                                                <div class="invalid-feedback" id="nombreError">El nombre debe contener solo letras.</div>
                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="phonenumberInput" class="form-label">Teléfono</label>
                                            <input type="text" class="form-control" id="phonenumberInput" name="telefono"
                                                value="{{ $user->telefono }}" maxlength="10">
                                                <div class="invalid-feedback" id="telefonoError">El teléfono debe contener solo números.</div>
                                        </div>
                                    </div>

                                    <!--end col-->
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Correo Electronico</label>
                                            <input type="email" class="form-control" id="email" name="email"
                                                id="email" value="{{ $user->email }}" required>
                                                <div class="invalid-feedback" id="emailError">Ingresa un correo electrónico válido.</div>
                                        </div>
                                    </div>


                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label">Foto de Perfil</label>
                                            <input type="file" id="image" name="image" class="form-control"
                                                accept="image/*" >
                                                
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div>
                                            <label for="newpasswordInput" class="form-label">Contraseña (dejar en blanco si
                                                no se quiere cambiar)</label>
                                            <input type="password" class="form-control" placeholder="8 - 15 Characteres"
                                                id="password" name="password">
                                                <div class="invalid-feedback" id="passwordError">La contraseña debe tener entre 8 y 15 caracteres.</div>

                                        </div>
                                    </div>
                                    <!--end col-->
                                    <div class="col-lg-4">
                                        <div>
                                            <label for="confirmpasswordInput" class="form-label">Confirmar
                                                Contraseña*</label>
                                            <input class="form-control" type="password" placeholder="8 - 15 Characteres"
                                                id="password_confirmation" name="password_confirmation">
                                                <div class="invalid-feedback" id="confirmPasswordError">Las contraseñas no coinciden.</div>
                                        </div>
                                    </div>

                                    <!--end col-->
                                    <div class="col-lg-12">
                                        <div class="hstack gap-3 justify-content-end">
                                            <button type="submit" class="btn btn-primary">Actualizar Perfil</button>

                                        </div>
                                    </div>
                                    <!--end col-->
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                        <!--end tab-pane-->

                    </div>
                    <!--end tab-pane-->
                </div>
            </div>
        </div>
    </div>
    <!--end col-->



    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    title: 'Éxito',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('updateProfileForm');
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, actualizar perfil!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Validar el campo de nombre de usuario
            const nameInput = document.getElementById('firstname');
            const nameError = document.getElementById('nombreError');

            nameError.style.display = 'none';

            nameInput.addEventListener('input', function() {
                const nameValue = nameInput.value;
                const isValidName = /^[a-zA-Z\s]+$/.test(nameValue);

                if (!isValidName) {
                    nameInput.classList.add('is-invalid');
                    nameError.style.display = 'block';
                } else {
                    nameInput.classList.remove('is-invalid');
                    nameError.style.display = 'none';
                }
            });

            // Validar el campo de teléfono
            const phoneInput = document.getElementById('phonenumberInput');
            const phoneError = document.getElementById('telefonoError');

            phoneError.style.display = 'none';

            phoneInput.addEventListener('input', function() {
                const phoneValue = phoneInput.value;
                const isValidPhone = /^[0-9]+$/.test(phoneValue);

                if (!isValidPhone) {
                    phoneInput.classList.add('is-invalid');
                    phoneError.style.display = 'block';
                } else {
                    phoneInput.classList.remove('is-invalid');
                    phoneError.style.display = 'none';
                }
            });

            // Validar el campo de email
            const emailInput = document.getElementById('email');
            const emailError = document.getElementById('emailError');

            emailError.style.display = 'none';

            emailInput.addEventListener('input', function() {
                const emailValue = emailInput.value;
                const isValidEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(emailValue);

                if (!isValidEmail) {
                    emailInput.classList.add('is-invalid');
                    emailError.style.display = 'block';
                } else {
                    emailInput.classList.remove('is-invalid');
                    emailError.style.display = 'none';
                }
            });

            // Validar la contraseña (mínimo 8 caracteres)
            const passwordInput = document.getElementById('password');
            const passwordError = document.getElementById('passwordError');

            passwordError.style.display = 'none';

            passwordInput.addEventListener('input', function() {
                const passwordValue = passwordInput.value;
                const isValidPassword = passwordValue.length >= 8 && passwordValue.length <= 15;

                if (!isValidPassword) {
                    passwordInput.classList.add('is-invalid');
                    passwordError.style.display = 'block';
                } else {
                    passwordInput.classList.remove('is-invalid');
                    passwordError.style.display = 'none';
                }
            });

            // Validar que las contraseñas coincidan
            const confirmPasswordInput = document.getElementById('password_confirmation');
            const confirmPasswordError = document.getElementById('confirmPasswordError');

            confirmPasswordError.style.display = 'none';

            confirmPasswordInput.addEventListener('input', function() {
                const confirmPasswordValue = confirmPasswordInput.value;
                const passwordValue = passwordInput.value;

                if (confirmPasswordValue !== passwordValue) {
                    confirmPasswordInput.classList.add('is-invalid');
                    confirmPasswordError.style.display = 'block';
                } else {
                    confirmPasswordInput.classList.remove('is-invalid');
                    confirmPasswordError.style.display = 'none';
                }
            });
        });
    </script>

@endsection
