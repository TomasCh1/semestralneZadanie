{{-- resources/views/auth/index.blade.php --}}
<x-guest-layout>
    <div
            x-data="{ tab: 'login' }"          {{-- aktuálna záložka --}}
    x-cloak                             {{-- skryté, kým sa nenačíta Alpine --}}
            class="max-w-md mx-auto mt-10"
    >
        <div class="flex border-b mb-6">
            <button
                    class="flex-1 py-2 text-center"
                    :class="tab === 'login' ? 'font-semibold border-b-2 border-indigo-600' : 'text-gray-500'"
                    @click="tab = 'login'"
            >
                Prihlásiť
            </button>

            <button
                    class="flex-1 py-2 text-center"
                    :class="tab === 'register' ? 'font-semibold border-b-2 border-indigo-600' : 'text-gray-500'"
                    @click="tab = 'register'"
            >
                Registrovať
            </button>
        </div>

        {{-- ======== PRIHLÁSENIE ================================================= --}}
        <div x-show="tab === 'login'"
                 x-transition:enter="transition ease-out duration-[4000]"
                 x-transition:enter-start="opacity-0 translate-y-2"
                 x-transition:enter-end="opacity-100 translate-y-0"
                {{--x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0"
                x-transition:leave-end="opacity-0 -translate-y-2"--}}
        >
            <!-- Skopírovaný obsah pôvodného login.blade.php -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div>
                    <x-input-label for="email" :value="'E‑mail'" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required autofocus />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="'Heslo'" />
                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required autocomplete="current-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="block">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300" name="remember">
                        <span class="ml-2 text-sm">Zapamätať si ma</span>
                    </label>
                </div>

                <div class="flex items-center justify-end">
                    <x-primary-button>Prihlásiť</x-primary-button>
                </div>
            </form>
        </div>

        {{-- ======== REGISTRÁCIA ================================================== --}}
        <div x-show="tab === 'register'"
             x-transition:enter="transition ease-out duration-[4000]"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
        >
            <!-- Skopírovaný obsah pôvodného register.blade.php -->
            <form method="POST" action="{{ route('register') }}" class="space-y-6">
                @csrf

                <!-- Name -->
                <div>
                    <x-input-label for="name" :value="'Meno'" />
                    <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" required autofocus />
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <x-input-label for="email" :value="'E‑mail'" />
                    <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" required />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <x-input-label for="password" :value="'Heslo'" />
                    <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" required autocomplete="new-password" />
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Confirm Password -->
                <div>
                    <x-input-label for="password_confirmation" :value="'Potvrďte heslo'" />
                    <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" required />
                </div>

                <div class="flex items-center justify-end">
                    <x-primary-button>Registrovať</x-primary-button>
                </div>
            </form>
        </div>
    </div>
</x-guest-layout>
