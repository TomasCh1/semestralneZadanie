<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('historyL.title') }}</h2>

    </x-slot>
    <div class="py-8 mt-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">

                    <div class="container mx-auto space-y-6">


                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('historyL.date')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('historyL.correct')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('historyL.total')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('historyL.score')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('historyL.city')}}</th>
                                    <th class="px-4 py-2 text-left text-gray-600">{{__('historyL.state')}}</th>
                                </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200">
                                @forelse ($testRuns as $run)
                                    @php
                                        $total = $run->questions->count();
                                        $correct = $run->questions->where('is_correct', true)->count();
                                        $score = $total ? round(($correct / $total) * 100, 1) : 0;
                                    @endphp
                                    <tr>
                                        <td class="px-4 py-2 text-gray-800">{{ \Carbon\Carbon::parse($run->started_at)->format('Y-m-d H:i') }}</td>
                                        <td class="px-4 py-2">{{ $correct }}</td>
                                        <td class="px-4 py-2">{{ $total }}</td>
                                        <td class="px-4 py-2">{{ $score }}%</td>
                                        <td class="px-4 py-2">{{ $run->city }}</td>
                                        <td class="px-4 py-2">{{ $run->state }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-4 text-center text-gray-500">
                                            No test history found.
                                        </td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
