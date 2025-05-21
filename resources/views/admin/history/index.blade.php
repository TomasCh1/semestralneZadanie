{{-- resources/views/admin/history/index.blade.php --}}

@extends('layouts.admin')
@php
    if (auth()->user()?->role_id !== 1) {
        header('Location: ' . route('dashboard'));
        exit;
    }
@endphp

@section('content')
    <div class="flex items-center justify-between mb-6">
        {{-- Názov stránky --}}
        <h1 class="text-2xl font-bold text-gray-800">{{ __('História testov') }}</h1>

        <div>
            {{-- Export CSV (biela farba textu na tmavomodrom podklade) --}}
            <a
                    href="{{ route('admin.history.export') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-blue-500 text-xs font-semibold uppercase rounded transition"
            >
                {{ __('Export CSV') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow-sm sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Anon. ID</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Začiatok</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ukončenie</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Mesto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Štát</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Otázok</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Správnych</th>
                <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase">Akcie</th>
            </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            @forelse($runs as $run)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $run->run_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $run->user_id ?? '–' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $run->anon_id ?? '–' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ \Illuminate\Support\Carbon::parse($run->started_at)->format('d.m.Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($run->finished_at)
                            {{ \Illuminate\Support\Carbon::parse($run->finished_at)->format('d.m.Y H:i') }}
                        @else
                            &ndash;
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $run->city ?? '–' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $run->state ?? '–' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $run->total_questions }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $run->correct_questions }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-center space-x-2">
                        {{-- Detail (teraz jasne viditeľné) --}}
                        <a
                                href="{{ route('admin.history.show', $run->run_id) }}"
                                class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-green-500 text-sm font-semibold uppercase rounded-md shadow transition duration-150"
                        >
                            {{ __('Detail') }}
                        </a>
                        {{-- Vymazať --}}
                        <form
                                action="{{ route('admin.history.destroy', $run->run_id) }}"
                                method="POST"
                                onsubmit="return confirm('{{ __('Naozaj chcete vymazať tento záznam?') }}');"
                                class="inline-block"
                        >
                            @csrf
                            @method('DELETE')
                            <button
                                    type="submit"
                                    class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold uppercase rounded transition"
                            >
                                {{ __('Vymazať') }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">
                        {{ __('Žiadne záznamy') }}
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $runs->links() }}
    </div>
@endsection
