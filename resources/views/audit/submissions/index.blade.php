<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Audit Dashboard') }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">
      @if(session('success'))
        <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded">
          {{ session('success') }}
        </div>
      @endif

      @if($submissions->isEmpty())
        <div class="bg-yellow-100 border-yellow-400 text-yellow-700 px-4 py-3 rounded">
          No submissions pending audit.
        </div>
      @else
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor Name</th>
                <th class="px-6 py-3"></th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($submissions as $sub)
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $sub->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $sub->user->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $sub->vendor_name }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right">
                  <a href="{{ route('audit.submissions.show', $sub) }}"
                  class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                    Review
                  </a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>
</x-app-layout>
