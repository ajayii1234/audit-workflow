{{-- resources/views/it/submissions/submitted.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Submitted to SAP') }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
      @if(session('success'))
        <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded mb-4">
          {{ session('success') }}
        </div>
      @endif

      <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
        <table id="sap-submitted-table" class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ref.</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Submitted to SAP At</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">I.T. (email)</th>
              <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
              <th class="px-4 py-2"></th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($submissions as $sub)
              <tr>
                <td class="px-4 py-2 whitespace-nowrap"></td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $sub->submission_reference }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $sub->vendor_name }}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">
                  {{ $sub->it_at ? $sub->it_at->format('M d, Y H:i') : '—' }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">
                  {{ optional($sub->itByUser)->email ?? ($sub->it_by ? \App\Models\User::find($sub->it_by)?->email : '—') }}
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-sm">
                  <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">
                    {{ ucfirst($sub->status) }}
                  </span>
                </td>
                <td class="px-4 py-2 whitespace-nowrap text-right">
                  <a href="{{ route('it.submissions.show', $sub) }}"
                     class="px-3 py-1 border rounded text-gray-700 hover:bg-gray-100">
                    View
                  </a>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  {{-- DataTables --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
  <script>
    $(function(){
      const table = $('#sap-submitted-table').DataTable({
        columnDefs: [{ orderable: false, searchable: false, targets: 0 }],
        order: [[ 3, 'desc' ]]
      });

      table.on('order.dt search.dt page.dt', function() {
        table.column(0, {search:'applied', order:'applied'}).nodes()
             .each((cell, i) => { cell.innerHTML = i + 1; });
      }).draw();
    });
  </script>
</x-app-layout>
