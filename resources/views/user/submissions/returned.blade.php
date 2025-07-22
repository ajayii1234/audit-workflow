{{-- resources/views/user/submissions/returned.blade.php --}}
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
          <table id="returned-table" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Returned On</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Returned By</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              @foreach($submissions as $sub)
                @php
                  $returnedAt = $sub->finance_at ?? $sub->audited_at;
                @endphp
                <tr>
                  <td class="px-6 py-4 whitespace-nowrap"></td>
                  <td class="px-6 py-4 whitespace-nowrap">{{ $sub->vendor_name }}</td>
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
                      Edit &amp; Resubmit
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

  {{-- Direct DataTables includes --}}
  <link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"
  />

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

  <script>
    $(function() {
      const table = $('#returned-table').DataTable({
        columnDefs: [
          { orderable: false, searchable: false, targets: 0 }
        ],
        order: [[2, 'desc']]
      });

      // On each draw/search/order/page, fill the first column with 1-based row numbers
      table.on('order.dt search.dt page.dt', function() {
        table.column(0, {search:'applied', order:'applied'}).nodes()
             .each((cell, i) => { cell.innerHTML = i + 1; });
      }).draw();
    });
  </script>
</x-app-layout>
