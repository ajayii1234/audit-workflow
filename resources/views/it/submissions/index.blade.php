<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">I.T. — Approved Submissions</h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      @if(session('success'))
        <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
      @endif

      @if($submissions->isEmpty())
        <div class="bg-yellow-100 border-yellow-400 text-yellow-700 px-4 py-3 rounded">No approved submissions awaiting SAP processing.</div>
      @else
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ref</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Approved On</th>
                <th class="px-6 py-3"></th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($submissions as $i => $sub)
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">{{ $i+1 }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ $sub->submission_reference }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ $sub->vendor_name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ optional($sub->finance_at)->format('M d, Y H:i') ?? $sub->created_at->format('M d, Y H:i') }}</td>
                  <td class="px-6 py-4 text-right space-x-2">
                    <a href="{{ route('it.submissions.show', $sub) }}" class="px-3 py-1 border rounded text-gray-700 hover:bg-gray-100">View</a>

                    <form method="POST" action="{{ route('it.submissions.update', $sub) }}" class="inline-block">
                      @csrf
                      @method('PUT')
                      <input type="hidden" name="it_submitted" value="1">
                      <button type="submit" class="px-3 py-1 bg-green-600 text-white rounded hover:bg-green-700">Mark submitted</button>
                    </form>
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
