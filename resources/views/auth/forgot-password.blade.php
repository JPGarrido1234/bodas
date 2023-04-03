<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
            </a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600" style="margin-left: 20%; margin-right: 20%;">
            ¿Has olvidado la contraseña o tienes problemas para acceder?
            <p>
                Introduce tu correo y te mandaremos las instrucciones para recuperar la contraseña y acceder a tu cuenta.
            </p>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input placeholder="Email" id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    Restablecer contraseña
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
