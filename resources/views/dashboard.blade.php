<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Testovanie matematiky
        </h2>
    </x-slot>

    <div class="py-8 mt-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="container mx-auto space-y-6">
                        <p>
                            Vitajte na našej platforme na testovanie zo stredoškolskej matematiky. Tu si môžete jednoducho vygenerovať test a počítať príklady s okamžitou odozvou.
                        </p>

                        <div class="bg-gray-100 p-4 rounded">
                            <h3 class="text-2xl font-semibold mb-2">Ako to funguje?</h3>
                            <ol class="list-decimal list-inside space-y-2">
                                <li>
                                    Kliknite na tlačidlo <span class="font-medium">„Začni test“</span>.
                                </li>
                                <li>
                                    Riešte test s náhodnými otázkami.
                                </li>
                                <li>
                                    Otázky s možnosťami majú vždy iba jednú správnu odpoveď.
                                </li>
                                <li>
                                    Na otvorené otázky odpovedajte v tvare "X=45" pokiaľ je X uvedené v rovnici.
                                </li>
                                <li>
                                    Na vyplnenie testu nie je časový limit. Pre každú otázku sa merie čas na nej strávený.
                                </li>
                                <li>
                                    Pri vyhodnotení vám ukáže aj kategórie otázok a vašu úspešnosť na jednotlivých témach.
                                </li>
                                <li>
                                    Prihlásení používatelia si výsledky môžu uložiť na svoj profil.
                                </li>
                            </ol>
                        </div>

                        <div class="text-center">
                            @if(Auth::check())
                                <p class="mb-4">
                                    Ahoj, <strong>{{ Auth::user()->name }}</strong>! Si pripravený?
                                </p>
                            @else
                                <p class="mb-4">
                                    Nie ste prihlásený/á. <a href="{{ route('login') }}" class="text-blue-600 underline">Prihláste sa</a> pre uloženie výsledkov alebo pokračujte ako hosť.
                                </p>
                            @endif
                                <x-dashboard-button class="ms-3">
                                    {{ __('Začať test') }}
                                </x-dashboard-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
