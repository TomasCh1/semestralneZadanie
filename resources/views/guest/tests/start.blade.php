@push('head')
    {{-- KaTeX --}}
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
                    {left:"$",right:"$",display:true},
                    {left:"\\(",right:"\\)",display:false}
                ],
                throwOnError:false
            });
        });
    </script>
@endpush

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">
            Otázky 1 – {{ $questions->count() }}
        </h2>
    </x-slot>

    <div class="py-8 mt-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    {{-- ▼▼▼ FORMULÁR CELÉHO TESTU ▼▼▼ --}}
                    <form id="guestTest" novalidate class="container mx-auto space-y-10">

                        @foreach ($questions as $idx => $question)
                            <div class="border rounded-xl p-6 space-y-4"
                                 data-type="{{ $question->type }}"
                                 data-qid="{{ $question->question_id }}"
                                 @if($question->type === 'MCQ')
                                     data-correct="{{ $question->choices->firstWhere('is_correct',1)->choice_id }}"
                                 @else
                                     data-answer="{{ strtolower(preg_replace('/\s+/','', $question->correct_answer)) }}"
                                    @endif>

                                {{-- text otázky --}}
                                <p class="question-text mb-6">
                                    {{ $idx+1 }}.&nbsp; {!! $question->question_text !!}
                                </p>

                                {{-- možnosti / text field --}}
                                @if ($question->type === 'MCQ')
                                    @foreach ($question->choices->shuffle() as $choice)
                                        <label class="block-1 mb-2">
                                            <input type="radio"
                                                   name="q_{{ $question->question_id }}"
                                                   value="{{ $choice->choice_id }}"
                                                   class="choices-quest me-2 accent-green-600">
                                            {!! $choice->text !!}
                                        </label>
                                    @endforeach
                                @else
                                    <textarea class="border rounded w-full p-2"
                                              rows="3"
                                              name="q_{{ $question->question_id }}"></textarea>
                                @endif
                            </div>
                        @endforeach

                        <x-dashboard-button type="submit" class="mt-4">
                            Vyhodnoť test
                        </x-dashboard-button>
                    </form>
                    {{-- ▲▲▲ KONIEC FORMULÁRA ▲▲▲ --}}

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

{{-- Toast + JS spracovanie --}}
<script>
    document.addEventListener('DOMContentLoaded', ()=>{

        /* toast */
        const toast = document.createElement('div');
        toast.id = 'toast';
        toast.textContent =
            'Musíte vybrať aspoň jednu odpoveď alebo napísať niečo do textového poľa.';
        document.body.appendChild(toast);

        const form      = document.getElementById('guestTest');
        const startTime = Date.now();

        form.addEventListener('submit', e=>{
            e.preventDefault();

            let correct = 0;
            const answers = [];

            /* prejdeme každú otázku */
            form.querySelectorAll('[data-qid]').forEach(div=>{
                const type = div.dataset.type;
                const qid  = div.dataset.qid;
                const inp  = type==='MCQ'
                    ? form.querySelector(`[name="q_${qid}"]:checked`)
                    : form.querySelector(`[name="q_${qid}"]`);
                const value = inp ? (inp.value || inp.textContent).trim() : '';

                if(!value){
                    toast.classList.add('show');
                    setTimeout(()=>toast.classList.remove('show'),3000);
                    return;
                }

                /* správnosť */
                let ok=false;
                if(type==='MCQ'){
                    ok = value === div.dataset.correct;
                }else{
                    ok = value.toLowerCase().replace(/\s+/g,'') === div.dataset.answer;
                }
                if(ok) correct++;

                answers.push({qid,value,ok});
            });

            /* ak niečo chýba, zastavíme submit (toast už bol zobrazený) */
            if(answers.some(a=>!a.value)) return;

            /* uložíme výsledok */
            const total     = answers.length;
            const percent   = Math.round((correct/total)*100);
            const timeSpent = Math.round((Date.now()-startTime)/1000);

            localStorage.setItem('guest_result', JSON.stringify({
                total,correct,percent,timeSpent,answers,
                finishedAt: new Date().toISOString()
            }));

            /* presmerovanie na /guest-test/finished */
            window.location.href = "{{ route('guest.finished') }}";
        });
    });
</script>
