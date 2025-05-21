@push('head')
    {{-- KaTeX: CSS + JS + auto-render, načíta sa len na tejto stránke --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/katex@0.16.10/dist/katex.min.css">
    <script defer
            src="https://cdn.jsdelivr.net/npm/katex@0.16.10/dist/katex.min.js"></script>
    <script defer
            src="https://cdn.jsdelivr.net/npm/katex@0.16.10/dist/contrib/auto-render.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            renderMathInElement(document.body, {
                delimiters: [
                    {left: "$", right: "$", display: true},
                    {left: "\\", right: "\\", display: false}
                ],
                throwOnError: false        // ak je vo vzorci preklep, stránka nespadne
            });
        });
    </script>
@endpush
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Výsledky testu</h2>
    </x-slot>

    <div class="py-8 mt-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="container mx-auto space-y-6">

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
                                        <ul class=" ml-6">
                                            @foreach($q->choices as $ch)

                                                @php
                                                    // Triedy pre stav odpovede
                                                    $isUser = $ch->choice_id == $row->user_answer;
                                                    $cls = 'choice-item ';
                                                    // ak je toto užívateľova voľba a nesprávna
                                                    if ($isUser && ! $ch->is_correct) {
                                                        $cls .= 'incorrect';
                                                    }
                                                    // ak je to správna odpoveď (či už ju user vybral alebo nie)
                                                    if ($ch->is_correct) {
                                                        $cls .= 'correct';
                                                    }
                                                @endphp
                                                <li class="{{ trim($cls) }}">
                                                    {{ chr(96 + $loop->iteration) }}) {{ $ch->text }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p>Vaša odpoveď: <code>{{ $row->user_answer }}</code></p>
                                        <p>Správna odpoveď: <code>{{ $q->correct_answer }}</code></p>
                                    @endif
                                    @if($q->areas->isNotEmpty())
                                        <p class="text-sm text-indigo-700 mt-1">
                                            <strong>Oblasti:</strong>
                                            {{ $q->areas->pluck('name')->join(', ') }}
                                        </p>
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
                </div>
            </div>
        </div>
    </div>

    <script>localStorage.removeItem('run{{ $run->run_id }}_used');</script>
</x-app-layout>
