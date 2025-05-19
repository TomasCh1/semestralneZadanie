<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('dashboardL.title') }}</h2>

    </x-slot>

    <div class="py-8 mt-2">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="container mx-auto space-y-6">
                        <p class="mb-6">{{ __('dashboardL.welcome') }}</p>
                        <div style="position: fixed; bottom: 0; right: 0; background: rgba(0,0,0,0.1); padding: 0.5em; font-size: 0.75em;">
                            session('locale') = {{ session('locale') }}<br>
                            app()->getLocale() = {{ app()->getLocale() }}
                        </div>


                        <div class="bg-gray-100 p-4 rounded">
                            <h2 class="text-2xl font-semibold mb-2">{{ __('dashboardL.how_it_works') }}</h2>
                            <ol class="list-decimal list-inside space-y-2">
                                <li>{{ __('dashboardL.steps.click_start') }}</li>
                                <li>{{ __('dashboardL.steps.solve_random') }}</li>
                                <li>{{ __('dashboardL.steps.one_answer') }}</li>
                                <li>{{ __('dashboardL.steps.format_open') }}</li>
                                <li>{{ __('dashboardL.steps.no_time_limit') }}</li>
                                <li>{{ __('dashboardL.steps.show_categories') }}</li>
                                <li>{{ __('dashboardL.steps.save_results') }}</li>
                            </ol>
                        </div>

                        <div class="text-center">
                            @if(Auth::check())
                                <p class="mb-4">
                                    {{ __('dashboardL.greeting', ['name' => Auth::user()->name]) }}
                                </p>
                            @else
                                <p class="mb-4">
                                    {!! __('dashboardL.guest_prompt', [
                                        'login_link' => '<a href="'.route('login').'" class="text-blue-600 underline">'.__('dashboardL.login').'</a>'
                                    ]) !!}
                                </p>
                            @endif

                            <div class="flex justify-center items-center">
                                <form method="POST" action="{{ route('test-runs.start') }}">
                                    @csrf
                                    <input type="hidden" name="timezone" id="tz">

                                    <x-dashboard-button type="submit">
                                        {{ __('dashboardL.start_test') }}
                                    </x-dashboard-button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
        document.getElementById('tz').value = tz ?? 'UTC';
    });
</script>
