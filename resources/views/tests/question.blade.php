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
        <h2 class="font-semibold text-xl">
            Otázka {{ $current + 1 }} / 15
        </h2>
    </x-slot>

    <div class="py-8 mt-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="container mx-auto space-y-6">
                        {{-- TEXT OTÁZKY – neescapovaný, aby KaTeX videl LaTeX --}}
                        <p class="question-text mb-6">{!! $question->question_text !!}</p>

                        <form method="POST"
                              action="{{ route('tests.answer', $run->run_id) }}"
                              id="qForm"
                              novalidate>
                            @csrf
                            <input type="hidden" name="question_id" value="{{ $question->question_id }}">
                            <input type="hidden" name="answer" id="answer-field">

                            @if ($question->type === 'MCQ')
                                @php
                                    $choices = DB::table('choices')
                                                 ->where('question_id', $question->question_id)
                                                 ->inRandomOrder()
                                                 ->get();
                                @endphp

                                @foreach ($choices as $choice)
                                    <label class="block-1 mb-2">
                                        <input class="choices-quest"
                                               type="radio"
                                               name="choice"
                                               value="{{ $choice->choice_id }}">
                                        {!! $choice->text !!}
                                    </label>
                                @endforeach
                            @else
                                <textarea id="textAnswer"
                                          class="border rounded w-full p-2"
                                          rows="3"></textarea>
                            @endif

                            <x-dashboard-button class="mt-4">
                                {{ $current === 14 ? 'Dokončiť test' : 'Ďalšia otázka' }}
                            </x-dashboard-button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Toast + form-validation script --}}

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1) Dynamicky vytvoríme toast element
            const toast = document.createElement('div');
            toast.id = 'toast';
            toast.textContent = 'Musíte vybrať aspoň jednu odpoveď alebo napísať niečo do textového poľa.';
            document.body.appendChild(toast);

            // 2) Hook na submit formulára
            const form = document.getElementById('qForm');
            form.addEventListener('submit', function (e) {
                const checked = document.querySelector('input[name="choice"]:checked');
                const txt = document.getElementById('textAnswer');

                // Ak nie je nič vyplnené/vybrané → zablokujeme a zobrazíme toast
                if (!checked && (!txt || !txt.value.trim())) {
                    e.preventDefault();
                    toast.classList.add('show');
                    setTimeout(() => toast.classList.remove('show'), 3000);
                    return;
                }

                // Ak validácia prešla, naplníme hidden field
                if (txt) {
                    document.getElementById('answer-field').value = txt.value.trim();
                } else {
                    document.getElementById('answer-field').value = checked.value;
                }
            });
        });
    </script>
</x-app-layout>
