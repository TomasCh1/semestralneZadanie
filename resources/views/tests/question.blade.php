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
                    { left: "$", right: "$", display: true },
                    { left: "\\", right: "\\", display: false }
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

    <div class="p-6">
        {{-- TEXT OTÁZKY – neescapovaný, aby KaTeX videl LaTeX --}}
        <p class="question-text mb-6">{!! $question->question_text !!}</p>

        <form method="POST"
              action="{{ route('tests.answer', $run->run_id) }}"
              id="qForm">
            @csrf
            <input type="hidden" name="question_id" value="{{ $question->question_id }}">
            <input type="hidden" name="answer"     id="answer-field">

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
                               value="{{ $choice->choice_id }}"
                               required>
                        {{-- TEXT ODPOVEDE – tiež neescapovaný kvôli vzorcom --}}
                        {!! $choice->text !!}
                    </label>
                @endforeach
            @else
                <textarea id="textAnswer"
                          class="border rounded w-full p-2"
                          rows="3" required></textarea>
            @endif

            <x-dashboard-button class="mt-4">
                {{ $current === 14 ? 'Dokončiť test' : 'Ďalšia otázka' }}
            </x-dashboard-button>
        </form>
    </div>

    <script>
        // pri odoslaní strč do hidden inputu správnu value
        document.getElementById('qForm').addEventListener('submit', () => {
            const txt = document.getElementById('textAnswer');
            if (txt)
                document.getElementById('answer-field').value = txt.value.trim();

            const sel = document.querySelector('input[name="choice"]:checked');
            if (sel)
                document.getElementById('answer-field').value = sel.value;
        });

        // záloha: odložiť použité question_id do localStorage
        const key  = 'run{{ $run->run_id }}_used';
        const used = JSON.parse(localStorage.getItem(key) || '[]');

        if (!used.includes({{ $question->question_id }})) {
            used.push({{ $question->question_id }});
            localStorage.setItem(key, JSON.stringify(used));
        }
    </script>
</x-app-layout>
