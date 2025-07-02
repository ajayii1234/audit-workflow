{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{ __("You're logged in!") }}

                    <!-- Your new link: -->
                    <div class="mt-4">
                        <a 
                            href="{{ url('/user/form') }}" 
                            class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100"
                        >
                            Go to User Form
                        </a>

                        <a 
                            href="{{ url('/audit/submissions') }}" 
                            class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100"
                        >
                            Go to Audit Page
                        </a>

                        <a 
                            href="{{ url('/finance/submissions') }}" 
                            class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100"
                        >
                            Go to Finance Page
                        </a>

                        <a 
                            href="{{ url('/admin/users') }}" 
                            class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100"
                        >
                            Go to Admin Page
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
