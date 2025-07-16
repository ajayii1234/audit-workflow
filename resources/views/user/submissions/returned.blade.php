<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Corrections Required') }}
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
          You have no submissions awaiting corrections.
        </div>
      @else
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Title</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Returned On</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Returned By</th>
                <th class="px-6 py-3"></th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($submissions as $sub)
                @php
                  // Determine which timestamp to show
                  $returnedAt = $sub->finance_at ?? $sub->audited_at;
                @endphp
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap">{{ $sub->id }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ $sub->title }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    {{ $returnedAt 
                        ? $returnedAt->format('M d, Y H:i') 
                        : '—' 
                    }}
                  </td>
                  <td class="px-6 py-4">
                    @if($sub->finance_at && $sub->finance_comment)
                      <div class="text-sm text-gray-700 whitespace-pre-wrap">
                        {{ $sub->finance_comment }}
                      </div>
                    @elseif($sub->audit_comment)
                      <div class="text-sm text-gray-700 whitespace-pre-wrap">
                        {{ $sub->audit_comment }}
                      </div>
                    @else
                      <span class="text-gray-500">—</span>
                    @endif
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    @if($sub->finance_at)
                      Finance ({{ optional($sub->financier)->email ?? '—' }})
                    @else
                      Audit ({{ optional($sub->auditor)->email ?? '—' }})
                    @endif
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-right">
                    <a href="{{ route('user.submissions.edit', $sub) }}"
                       class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                      Edit & Resubmit
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
