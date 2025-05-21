<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('dashboardL.title') }}
        </h2>
    </x-slot>

    {{-- skrytý iframe → vďaka nemu sa PDF stiahne bez presmerovania/refreshu --}}
    <iframe id="pdf-download-frame" style="display:none;"></iframe>

    <div class="py-8 mt-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="container mx-auto space-y-6">

                        {{-- úvodný text --}}
                        <p class="mb-6">{{ __('dashboardL.welcome') }}</p>

                        {{-- „Ako to funguje“ --}}
                        <div class="bg-gray-100 p-4 rounded">
                            <h2 class="text-2xl font-semibold mb-2">
                                {{ __('dashboardL.how_it_works') }}
                            </h2>

                            {{-- dynamicky zoznam krokov – žiadne ručné <li> --}}
                            <ol class="list-decimal list-inside space-y-2">
                                @foreach(__('dashboardL.steps') as $step)
                                    <li>{{ $step }}</li>
                                @endforeach
                            </ol>
                        </div>

                        {{-- privítanie + tlačidlá --}}
                        <div class="text-center">
                            @if(Auth::check())
                                <p class="mb-4">
                                    {{ __('dashboardL.greeting', ['name' => Auth::user()->name]) }}
                                </p>

                                <div class="flex flex-col sm:flex-row justify-center items-center gap-4">
                                    {{-- START TEST --}}
                                    <form method="POST" action="{{ route('test-runs.start') }}">
                                        @csrf
                                        <input type="hidden" name="timezone" id="tz">
                                        <x-dashboard-button type="submit">
                                            {{ __('dashboardL.start_test') }}
                                        </x-dashboard-button>
                                    </form>

                                    {{-- STIAHNUŤ PDF – stránka sa nemení --}}
                                    <x-dashboard-button type="button"
                                                        onclick="document.getElementById('pdf-download-frame').src='{{ route('dashboard.pdf') }}'">
                                        {{ __('PDF') }}
                                    </x-dashboard-button>
                                </div>
                            @else
                                {{-- hosť (guest) --}}
                                <p class="mb-4">
                                    {!! __('dashboardL.guest_prompt', [
                                        'login_link' => '<a href="'.route('login').'"

                                                         class="text-blue-600 underline">
                                                         '.__('dashboardL.login').'</a>'
                                    ]) !!}
                                </p>

                                <div class="flex justify-center items-center">
                                    <x-dashboard-button type="button"
                                                        onclick="window.location.href='{{ route('guest.start') }}'">
                                        {{ __('dashboardL.start_test') }}
                                    </x-dashboard-button>

                                </div>
                                <x-dashboard-button type="button"
                                                    onclick="document.getElementById('pdf-download-frame').src='{{ route('dashboard.pdf') }}'">
                                    {{ __('PDF') }}
                                </x-dashboard-button>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- timezone doplníme len prihlásenému (keď existuje #tz) --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tzInput = document.getElementById('tz');
        if (tzInput) {
            tzInput.value = Intl.DateTimeFormat().resolvedOptions().timeZone ?? 'UTC';
        }
    });
</script>
