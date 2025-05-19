<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Výsledky testu</h2>
    </x-slot>

    <div class="p-6">
        <p class="text-lg mb-4">
            Správne:
            <strong>{{ $stats->ok }}</strong> /
            <strong>{{ $stats->total }}</strong><br>

            Čas:
            <strong>
                {{ \Carbon\CarbonInterval::seconds(abs($stats->secs))
                      ->cascade()                 // 3600 ➔ 1 h
                      ->format('%h:%I:%S') }}     {{-- hodiny bez limitu --}}
            </strong>        </p>

        <x-dashboard-button onclick="location.href='{{ route('dashboard') }}'">
            Späť na dashboard
        </x-dashboard-button>
    </div>

    <script>localStorage.removeItem('run{{ $run->run_id }}_used');</script>
</x-app-layout>
