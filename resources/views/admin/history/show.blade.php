{{-- resources/views/admin/history/show.blade.php --}}

@extends('layouts.admin')

@section('content')
    <div class="flex items-center justify-between mb-6">
        {{-- Názov stránky --}}
        <h1 class="text-2xl font-bold text-gray-800">{{ __('Detaily test runu') }}</h1>

        {{-- Tlačidlo na export CSV --}}
        <a
                href="{{ route('admin.history.export') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-xs font-semibold uppercase rounded hover:bg-blue-500 focus:outline-none transition"
        >
            {{ __('Export CSV') }}
        </a>
    </div>

    {{-- Odkaz späť na zoznam --}}
    <div class="mb-4">
        <a href="{{ route('admin.history.index') }}" class="text-blue-600 hover:underline">
            ← {{ __('Späť na zoznam') }}
        </a>
    </div>

    {{-- Detaily runu --}}
    <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg p-6">
        <dl class="grid grid-cols-2 gap-x-4 gap-y-2">
            <dt class="font-medium text-gray-600">{{ __('Run ID') }}</dt>
            <dd>{{ $run->run_id }}</dd>

            <dt class="font-medium text-gray-600">{{ __('User ID') }}</dt>
            <dd>{{ $run->user_id ?? '–' }}</dd>

            <dt class="font-medium text-gray-600">{{ __('Anon. ID') }}</dt>
            <dd>{{ $run->anon_id ?? '–' }}</dd>

            <dt class="font-medium text-gray-600">{{ __('Začiatok') }}</dt>
            <dd>{{ \Illuminate\Support\Carbon::parse($run->started_at)->format('d.m.Y H:i') }}</dd>

            <dt class="font-medium text-gray-600">{{ __('Ukončenie') }}</dt>
            <dd>
                @if($run->finished_at)
                    {{ \Illuminate\Support\Carbon::parse($run->finished_at)->format('d.m.Y H:i') }}
                @else
                    &ndash;
                @endif
            </dd>

            <dt class="font-medium text-gray-600">{{ __('Mesto') }}</dt>
            <dd>{{ $run->city ?? '–' }}</dd>

            <dt class="font-medium text-gray-600">{{ __('Štát') }}</dt>
            <dd>{{ $run->state ?? '–' }}</dd>

            <dt class="font-medium text-gray-600">{{ __('Počet otázok') }}</dt>
            <dd>{{ $run->total_questions }}</dd>

            <dt class="font-medium text-gray-600">{{ __('Počet správnych') }}</dt>
            <dd>{{ $run->correct_questions }}</dd>
        </dl>

        {{-- Tabuľka otázok --}}
        <div class="mt-6">
            <h2 class="text-lg font-semibold text-gray-800">{{ __('Otázky') }}</h2>
            <div class="overflow-x-auto mt-2">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID otázky</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Text otázky</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Správne</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($questions as $q)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $q->id }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $q->text }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($q->is_correct)
                                    <span class="text-green-600 font-semibold">✔️</span>
                                @else
                                    <span class="text-red-600 font-semibold">❌</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
