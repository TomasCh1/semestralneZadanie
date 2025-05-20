<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Výsledky testu</h2>
    </x-slot>

    <div class="p-6 space-y-6">

        {{-- Súhrn --}}
        <p class="text-lg">
            Správne:
            <strong>{{ $stats->ok }}</strong> /
            <strong>{{ $stats->total }}</strong><br>
            Čas:
            <strong>
                {{ \Carbon\CarbonInterval::seconds(abs($stats->secs))
                       ->cascade()->format('%h:%I:%S') }}
            </strong>
        </p>

        {{-- Detailné otázky --}}
        <div class="space-y-4">
            @foreach($details as $row)
                @php
                    $q   = $row->question;
                    $ok  = $row->is_correct;
                    $cls = $ok ? 'text-green-600' : 'text-red-600';
                @endphp

                <div class="border p-4 rounded bg-gray-50">
                    <p class="font-medium {{ $cls }}">
                        {{ $row->shown_order }}. {{ $q->question_text }}
                        – <span>{{ $ok ? '✓ správne' : '✗ zle' }}</span>
                    </p>

                    {{-- MCQ – zobraz možnosti a zvýrazni správnu --}}
                    @if($q->type === 'MCQ')
                        <ul class="list-disc ml-6">
                            @foreach($q->choices as $ch)
                                <li
                                        @class([
                                            'font-semibold' => $ch->is_correct,
                                            'underline'     => $ch->choice_id == $row->user_answer,
                                        ])
                                >
                                    {{ $ch->text }}
                                    @if($ch->is_correct) (správna)@endif
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>Vaša odpoveď: <code>{{ $row->user_answer }}</code></p>
                        <p>Správna odpoveď: <code>{{ $q->correct_answer }}</code></p>
                    @endif

                    <p class="text-sm text-gray-500 mt-1">
                        Čas: {{\Carbon\CarbonInterval::seconds($row->time_spent_sec)
                       ->cascade()->format('%h:%I:%S')  }}
                    </p>
                </div>
            @endforeach
        </div>

        <x-dashboard-button onclick="location.href='{{ route('dashboard') }}'">
            Späť na dashboard
        </x-dashboard-button>
    </div>

    <script>localStorage.removeItem('run{{ $run->run_id }}_used');</script>
</x-app-layout>
