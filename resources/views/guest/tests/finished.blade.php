<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Výsledok testu</h2>
    </x-slot>

    <div class="py-8 mt-2">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div id="resultBox" class="space-y-4"></div>

                    <x-dashboard-button
                            type="button"
                            onclick="window.location.href='{{ route('guest.start') }}'">
                        {{ __('Restart') }}
                    </x-dashboard-button>
                    <x-dashboard-button
                            type="button"
                            onclick="window.location.href='{{ route('dashboard') }}'">
                        {{ __('Menu') }}
                    </x-dashboard-button>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    document.addEventListener('DOMContentLoaded',()=>{
        const box = document.getElementById('resultBox');
        const data = JSON.parse(localStorage.getItem('guest_result')||'{}');

        if(!data.total){
            box.innerHTML='<p class="text-red-600">Žiadny výsledok nenájdený &nbsp;–&nbsp; spusť test.</p>';
            return;
        }

        box.innerHTML = `
        <p><strong>Správne:</strong> ${data.correct} / ${data.total}</p>
        <p><strong>Úspešnosť:</strong> ${data.percent}%</p>
        <p><strong>Čas:</strong> ${data.timeSpent} s</p>
        <p class="text-sm text-gray-600">Dátum: ${new Date(data.finishedAt).toLocaleString()}</p>
    `;
    });
</script>
