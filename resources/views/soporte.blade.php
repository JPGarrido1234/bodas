@section('titulo', 'Soporte')

<x-guest-layout>
    <x-auth-card>
        <x-slot name="logo">
            <a href="/">
                <img src="https://bodegascampos.com/wp-content/uploads/2022/10/logo-web-transparente.png" alt="">
            </a>
        </x-slot>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Validation Errors -->
        <x-auth-validation-errors class="mb-4" :errors="$errors" />

        <form method="POST" action="{{ route('soporte.enviar') }}">
            @csrf
            <div>
                <x-label for="ref" :value="__('REF Boda (opcional)')" />
                <x-input id="ref" class="block mt-1 w-full" type="text" name="ref" :value="old('ref')" autofocus />
            </div>
            <br>
            <div>
                <x-label for="fullname" :value="__('Nombre completo')" />
                <x-input id="fullname" class="block mt-1 w-full" type="text" name="fullname" :value="old('fullname')"/>
            </div>
            <br>
            <div>
                <x-label for="subject" :value="__('Asunto')" />
                <x-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject')" />
            </div>
            <br>
            <div>
                <x-label for="msg" :value="_('Mensaje')"></x-label>
                <textarea name="msg" id="msg" rows="10" class="rounded-md border-gray-300 w-full block"></textarea>
            </div>

            <div class="block mt-4">
                <label for="politica" class="inline-flex items-center">
                    <input id="politica" type="checkbox" style="color:#560f15;" class="rounded border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" name="politica">
                    <span class="ml-2 text-sm text-gray-600">He leído y acepto la <u><a href="https://bodegascampos.com/politica-de-privacidad/" target="_blank">política de privacidad</a></u></span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button class="ml-3" style="background-color:#560f15;">
                    {{ __('Enviar') }}
                </x-button>
            </div>
        </form>
    </x-auth-card>
</x-guest-layout>
