{{-- resources/views/admin/history/index.blade.php --}}

@extends('layouts.admin')
@php
    if (auth()->user()?->role_id !== 1) {
        header('Location: ' . route('dashboard'));
        exit;
    }
@endphp

<x-app-layout>
    {{-- Názov stránky --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('adminL.history_title') }}</h2>
        <div>
            <a
                href="{{ route('admin.history.export') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-blue-500 text-xs font-semibold uppercase rounded transition"
            >
                {{ __('adminL.export') }}
            </a>
        </div>
    </x-slot>
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif
    <div class="py-8 mt-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="container mx-auto space-y-6">
                        <div class="overflow-x-auto px-4">
                            <table class="w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('adminL.id')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('adminL.user_id')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('adminL.anon_id')}}</th>
                                    <th class="px-4 py-2  text-gray-600 align-middle text-center">{{__('adminL.start')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('adminL.end')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('adminL.city')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('adminL.country')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('adminL.no_questions')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('adminL.correct_answers')}}</th>
                                    <th class="px-4 py-2  text-gray-600 align-middle text-center">{{__('adminL.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
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
                                            <a
                                                href="{{ route('admin.history.details', ['userId' => $run->user_id, 'runId' => $run->run_id]) }}"
                                                class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-green-500 text-sm font-semibold uppercase rounded-md shadow transition duration-150"
                                            >
                                                {{ __('adminL.details') }}
                                            </a>
                                            <form
                                                action="{{ route('admin.history.destroy', $run->run_id) }}"
                                                method="POST"
                                                onsubmit="return confirm('{{ __('adminL.confirm_delete') }}');"
                                                class="inline-block"
                                            >
                                                @csrf
                                                @method('DELETE')
                                                <button
                                                    type="submit"
                                                    class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs font-semibold uppercase rounded transition"
                                                >
                                                    {{ __('adminL.delete') }}
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">
                                            {{__ ('adminL.no_records') }}
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                            <div class="mb-4">
                                <a href="{{ route('admin.history.index') }}" class="text-blue-600 hover:underline">
                                    ← {{ __('adminL.back') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-6 flex justify-center">
        {{ $runs->links() }}
    </div>
</x-app-layout>>
