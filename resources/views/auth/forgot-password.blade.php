<x-guest-layout>
    <x-jet-authentication-card>
        <x-slot name="logo">
            <a href="/"><img class="mx-auto w-1/4" src="/images/springs-logo.png" /></a>
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            {{ __('auth.forgot_password_text') }}
        </div>

        @if (session('status'))
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ session('status') }}
            </div>
        @endif

        <x-jet-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <div class="block">
                <x-jet-label value="{{ __('auth.email') }}" />
                <x-jet-input class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-jet-button>
                    {{ __('auth.email_password_reset_link') }}
                </x-jet-button>
            </div>
        </form>
    </x-jet-authentication-card>
</x-guest-layout>
