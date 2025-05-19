<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Otázka {{ $current + 1 }} / 15
        </h2>
    </x-slot>

    <div class="p-6">
        <p class="mb-6 text-lg font-medium">{{ $question->question_text }}</p>

        <form method="POST" action="{{ route('tests.answer', $run->run_id) }}" id="qForm">
            @csrf
            <input type="hidden" name="question_id" value="{{ $question->question_id }}">
            <input type="hidden" name="answer"     id="answer-field">

            @if($question->type === 'MCQ')
                @php
                    $choices = DB::table('choices')
                                 ->where('question_id', $question->question_id)
                                 ->inRandomOrder()
                                 ->get();
                @endphp

                @foreach($choices as $choice)
                    <label class="block mb-2">
                        <input type="radio" name="choice" value="{{ $choice->choice_id }}" required>
                        {{ $choice->text }}
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
            if (txt) document.getElementById('answer-field').value = txt.value.trim();

            const sel = document.querySelector('input[name="choice"]:checked');
            if (sel) document.getElementById('answer-field').value = sel.value;
        });

        // záloha: odložiť použité question_id do localStorage
        const key  = 'run{{ $run->run_id }}_used',
            used = JSON.parse(localStorage.getItem(key) || '[]');
        if (!used.includes({{ $question->question_id }})) {
            used.push({{ $question->question_id }});
            localStorage.setItem(key, JSON.stringify(used));
        }
    </script>
</x-app-layout>
