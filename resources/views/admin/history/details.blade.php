{{-- resources/views/admin/history/show.blade.php --}}

@extends('layouts.admin')
@php
    if (auth()->user()?->role_id !== 1) {
        header('Location: ' . route('dashboard'));
        exit;
    }
@endphp

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('adminL.details_title') }}{{$run->run_id}}</h2>
    </x-slot>
    <div class="py-8 mt-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="container mx-auto space-y-6">
                        <div class="bg-white overflow-x-auto shadow-sm sm:rounded-lg p-6">
                            <div class="mb-4">
                                <a href="{{ route('admin.history.show', $run->user_id) }}"
                                   class="text-blue-600 hover:underline">
                                    ← {{ __('adminL.back') }}
                                </a>
                            </div>
                            <dl class="grid grid-cols-2 gap-x-4">
                                <dt class="font-medium text-gray-600">{{ __('adminL.run_id') }}</dt>
                                <dd>{{ $run->run_id }}</dd>

                                <dt class="font-medium text-gray-600">{{ __('adminL.user_id') }}</dt>
                                <dd>{{ $run->user_id ?? '–' }}</dd>
                                <dt class="font-medium text-gray-600">{{__('adminL.start') }}</dt>
                                <dd>{{ \Illuminate\Support\Carbon::parse($run->started_at)->format('d.m.Y H:i') }}</dd>

                                <dt class="font-medium text-gray-600">{{__('adminL.end') }}</dt>
                                <dd>
                                    @if($run->finished_at)
                                        {{ \Illuminate\Support\Carbon::parse($run->finished_at)->format('d.m.Y H:i') }}
                                    @else
                                        &ndash;
                                    @endif
                                </dd>
                                <dt class="font-medium text-gray-600">{{__('adminL.correct_answers') }}</dt>
                                <dd>{{ $run->correct_questions }}</dd>
                            </dl>

                            {{-- Tabuľka otázok --}}
                            <div class="mt-6">
                                <h2 class="text-lg font-semibold text-gray-800">{{__('adminL.no_questions') }}</h2>
                                <div class="overflow-x-auto mt-2">
                                    <table class="w-full divide-y divide-gray-200 text-sm">
                                        <thead class="bg-gray-100">
                                        <tr>
                                            <th class="px-4 py-2 text-left text-gray-600">{{__('adminL.question_id') }}</th>
                                            <th class="px-4 py-2 text-left text-gray-600">{{__('adminL.question') }}</th>
                                            <th class="px-4 py-2 text-left text-gray-600">{{__('adminL.correct') }}</th>
                                        </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
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
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>>
