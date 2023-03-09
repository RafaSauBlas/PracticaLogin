<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('logeocodigo') }}">
        @csrf
        <!-- Email Address -->
        <div>
            <x-input-label for="text" :value="__('Codigo de verificación')" />
            <x-text-input id="codigo" class="block mt-1 w-full" type="text" name="codigo" :value="old('codigo')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('text')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button class="ml-3">
                Login
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>