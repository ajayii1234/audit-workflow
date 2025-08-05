{{-- resources/views/user/submissions/search.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Search Submissions') }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

      {{-- Search form only --}}
      <form method="GET" action="{{ route('user.submissions.search') }}"
            class="mb-6 flex">
        <input
          name="term"
          type="text"
          value="{{ old('term', $term ?? '') }}"
          placeholder="Search…"
          class="block w-full border-gray-300 rounded-l-md shadow-sm p-2 focus:ring-indigo-500 focus:border-indigo-500"
        />
        <button
          type="submit"
          class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100"
        >
          Search
        </button>
      </form>

      {{-- Results only if searched --}}
      @if(!empty($term))
        @if($submissions->isEmpty())
          <div class="bg-yellow-100 border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            No submissions found for “{{ $term }}”.
          </div>
        @else
          <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
            <table id="search-table"
                   class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Vendor Name</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Submitted On</th>
                  <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                  <th class="px-4 py-2"></th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @foreach($submissions as $i => $sub)
                  <tr>
                    <td class="px-4 py-2 whitespace-nowrap"></td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $sub->id }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $sub->vendor_name }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-700">{{ $sub->created_at->format('M d, Y H:i') }}</td>
                    <td class="px-4 py-2 whitespace-nowrap text-sm">
                      @switch($sub->status)
                        @case('pending_audit') Pending Audit @break
                        @case('pending_finance') Pending Finance @break
                        @case('returned_to_user') Returned @break
                        @case('approved') Approved @break
                        @default —
                      @endswitch
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap text-right">
                      @if($sub->status==='returned_to_user')
                        <a href="{{ route('user.submissions.edit',$sub) }}"
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
      @endif

    </div>
  </div>

  {{-- DataTables includes only if searched --}}
  @if(!empty($term))
    <link rel="stylesheet"
          href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" />
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
      $(function(){
        const tbl = $('#search-table').DataTable({
          columnDefs:[{ orderable:false, searchable:false, targets:0 }],
          order:[[1,'asc']]
        });
        tbl.on('order.dt search.dt page.dt', ()=>{
          tbl.column(0,{search:'applied',order:'applied'})
             .nodes().each((cell,i)=>cell.innerHTML=i+1);
        }).draw();
      });
    </script>
  @endif
</x-app-layout>
