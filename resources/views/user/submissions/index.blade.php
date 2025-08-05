{{-- resources/views/user/submissions/index.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('My Submissions') }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      @if($submissions->isEmpty())
        <div class="bg-yellow-100 border-yellow-400 text-yellow-700 px-4 py-3 rounded">
          You have not submitted any forms yet.
        </div>
      @else
        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
          <table id="submissions-table" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ref.</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Vendor Name</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Submitted On</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Comment</nth>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Returned By</th>
                <th class="px-4 py-2"></th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($submissions as $sub)
                @php
                  $comment = $sub->status === 'returned_to_user'
                    ? ($sub->finance_at ? $sub->finance_comment : $sub->audit_comment)
                    : null;
                  $returnedBy = $sub->status === 'returned_to_user'
                    ? ($sub->finance_at ? 'Finance ('.$sub->financier->email.')' : 'Audit ('.$sub->auditor->email.')')
                    : null;
                @endphp
                <tr>
                  <td class="px-4 py-2 whitespace-nowrap"></td>
                  <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $sub->submission_reference }}</td>
                  <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $sub->vendor_name }}</td>
                  <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">
                    {{ $sub->created_at->format('M d, Y H:i') }}
                  </td>
                  <td class="px-4 py-2 whitespace-nowrap text-sm">
                    @switch($sub->status)
                      @case('pending_audit')
                        <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                          Pending Audit
                        </span>
                        @break
                      @case('pending_finance')
                        <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                          Pending Finance
                        </span>
                        @break
                      @case('returned_to_user')
                        <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800">
                          Returned
                        </span>
                        @break
                      @case('approved')
                        <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                          Approved
                        </span>
                        @break
                      @default
                        <span class="text-gray-500">—</span>
                    @endswitch
                  </td>
                  <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">
                    {{ $comment ?? '—' }}
                  </td>
                  <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">
                    {{ $returnedBy ?? '—' }}
                  </td>
                  <td class="px-4 py-2 whitespace-nowrap text-right">
                    @if($sub->status === 'returned_to_user')
                      <a href="{{ route('user.submissions.edit', $sub) }}"
                         class="px-3 py-1 border rounded text-gray-700 hover:bg-gray-100">
                        Edit
                      </a>
                    @endif
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

  <!-- Datatables includes like returned view -->
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
  <script>
    $(function() {
      const table = $('#submissions-table').DataTable({
        columnDefs: [
          { orderable: false, searchable: false, targets: 0 }
        ],
        order: [[1, 'asc']]
      });

      table.on('order.dt search.dt page.dt', function() {
        table.column(0, { search:'applied', order:'applied' })
             .nodes()
             .each((cell, i) => cell.innerHTML = i + 1 );
      }).draw();
    });
  </script>
</x-app-layout>
