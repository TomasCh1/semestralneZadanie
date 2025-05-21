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
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('adminL.index_title') }}</h2>
        <div>
            {{-- Export CSV (biela farba textu na tmavomodrom podklade) --}}
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
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('adminL.user_id')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('adminL.name')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('adminL.email')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600 ">{{__('adminL.actions')}}</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->user_id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->user_name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->user_email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-left">
                                            <a
                                                href="{{ route('admin.history.show', $user->user_id) }}"
                                                class="inline-flex items-center px-3 py-1 bg-indigo-600 hover:bg-indigo-700 text-green-500 text-sm font-semibold uppercase rounded-md shadow transition duration-150"
                                            >
                                                {{ __('adminL.details') }}
                                            </a>
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
</x-app-layout>>
