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
        @php
          // Group by reference and then sort groups by the latest id (desc) so newest groups appear first
          $groups = $submissions
                    ->groupBy('submission_reference')
                    ->sortByDesc(function ($items, $key) {
                        // $items is a Collection of rows for the same reference
                        return $items->max('id'); // latest id per group
                    });
        @endphp

        <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
          <table id="returned-table" class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ref.</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Vendor Name</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Returned On</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Comment</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Returned By</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              {{-- Render only the latest row per submission_reference (groups already sorted newest-first) --}}
              @foreach($groups as $ref => $items)
                @php
                  // latest item for this reference (by id)
                  $latest = $items->sortByDesc('id')->first();
                  $returnedAt = $latest->finance_at ?? $latest->audited_at;
                  $hasHistory = $items->count() > 1;
                @endphp

                <tr>
                  <td class="px-6 py-4 whitespace-nowrap"></td>

                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ $ref }}
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ $latest->vendor_name }}
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    {{ $returnedAt ? $returnedAt->format('M d, Y H:i') : '—' }}
                  </td>

                  <td class="px-6 py-4 text-sm text-gray-700">
                    @if($latest->finance_at && $latest->finance_comment)
                      <div class="whitespace-pre-wrap">{{ $latest->finance_comment }}</div>
                    @elseif($latest->audit_comment)
                      <div class="whitespace-pre-wrap">{{ $latest->audit_comment }}</div>
                    @else
                      <span class="text-gray-500">—</span>
                    @endif
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                    @if($latest->finance_at)
                      Finance ({{ optional($latest->financier)->email ?? '—' }})
                    @else
                      Audit ({{ optional($latest->auditor)->email ?? '—' }})
                    @endif
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap text-right space-x-2">
                    {{-- History button (only if there is actual history) --}}
                    @if($hasHistory)
                      <button
                        type="button"
                        class="history-btn inline-flex items-center px-3 py-1 border rounded text-gray-700 hover:bg-gray-100"
                        data-ref="{{ $ref }}"
                      >
                        History
                      </button>
                    @endif

                    {{-- Edit only for the latest returned entry (user can resubmit) --}}
                    @if($latest->status === 'returned_to_user')
                      <a href="{{ route('user.submissions.edit', $latest) }}"
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

        {{-- Pre-render modals for groups that have history (count > 1) --}}
        @foreach($groups as $ref => $items)
          @if($items->count() > 1)
            <div
              id="history-modal-{{ $ref }}"
              class="history-modal fixed inset-0 z-50 hidden items-center justify-center"
              aria-hidden="true"
            >
              <div class="modal-backdrop absolute inset-0 bg-black opacity-50"></div>

              <div class="relative bg-white rounded-lg shadow-lg w-11/12 md:w-3/4 lg:w-2/3 z-10 max-h-[85vh] overflow-auto">
                <div class="flex items-start justify-between p-4 border-b">
                  <h3 class="text-lg font-semibold">History — Ref: {{ $ref }}</h3>
                  <button class="modal-close text-gray-600 hover:text-gray-900" data-ref="{{ $ref }}">
                    ✕
                  </button>
                </div>

                <div class="p-4">
                  <div class="mb-3 text-sm text-gray-600">
                    Showing all versions for this reference (newest first).
                  </div>

                  <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                      <thead class="bg-gray-50 sticky top-0">
                        <tr>
                          <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                          <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                          <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Vendor</th>
                          <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Created</th>
                          <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Returned On</th>
                          <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Returned By</th>
                          <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase">Comment</th>
                        </tr>
                      </thead>
                      <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($items->sortByDesc('id') as $it)
                          @php $retAt = $it->finance_at ?? $it->audited_at; @endphp
                          <tr>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ $it->id }}</td>
                            <td class="px-3 py-2 text-sm">
                              @switch($it->status)
                                @case('pending_audit')
                                  <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Audit</span>
                                  @break
                                @case('pending_finance')
                                  <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Pending Finance</span>
                                  @break
                                @case('returned_to_user')
                                  <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-red-100 text-red-800">Returned</span>
                                  @break
                                @case('approved')
                                  <span class="px-2 inline-flex text-xs font-semibold rounded-full bg-green-100 text-green-800">Approved</span>
                                  @break
                                @default
                                  <span class="text-gray-500">—</span>
                              @endswitch
                            </td>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ $it->vendor_name }}</td>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ $it->created_at->format('M d, Y H:i') }}</td>
                            <td class="px-3 py-2 text-sm text-gray-700">{{ $retAt ? $retAt->format('M d, Y H:i') : '—' }}</td>
                            <td class="px-3 py-2 text-sm text-gray-700">
                              @if($it->finance_at)
                                Finance ({{ optional($it->financier)->email ?? '—' }})
                              @else
                                Audit ({{ optional($it->auditor)->email ?? '—' }})
                              @endif
                            </td>
                            <td class="px-3 py-2 text-sm text-gray-700 whitespace-pre-wrap">
                              {{ $it->finance_comment ?? $it->audit_comment ?? '—' }}
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                    </table>
                  </div>

                  <div class="mt-4 text-right">
                    <button class="modal-close px-4 py-2 border rounded text-gray-700 hover:bg-gray-100" data-ref="{{ $ref }}">Close</button>
                  </div>
                </div>
              </div>
            </div>
          @endif
        @endforeach

      @endif
    </div>
  </div>

  {{-- Direct DataTables includes --}}
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css"/>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

  <script>
    $(function() {
      // Initialise DataTable and numbering (preserve DOM order which we set server-side)
      const table = $('#returned-table').DataTable({
        columnDefs: [{ orderable: false, searchable: false, targets: 0 }],
        order: [],     // <-- IMPORTANT: no initial ordering so DOM order (server-sorted) is preserved
        paging: true,
        pageLength: 10
      });

      table.on('order.dt search.dt page.dt', function() {
        table.column(0, {search:'applied', order:'applied'}).nodes()
             .each((cell, i) => cell.innerHTML = i + 1 );
      }).draw();

      // Show modal when History clicked
      $('.history-btn').on('click', function() {
        const ref = $(this).data('ref');
        const modal = $('#history-modal-' + ref);
        if (!modal.length) return;
        modal.removeClass('hidden').addClass('flex');
        $('body').css('overflow', 'hidden');
      });

      // Close modal on close button or backdrop click
      $('.modal-close').on('click', function() {
        const ref = $(this).data('ref');
        const modal = $('#history-modal-' + ref);
        if (!modal.length) return;
        modal.addClass('hidden').removeClass('flex');
        $('body').css('overflow', '');
      });

      // clicking backdrop closes modal
      $('.history-modal').on('click', function(e) {
        if (e.target === this) {
          $(this).addClass('hidden').removeClass('flex');
          $('body').css('overflow', '');
        }
      });

      // Escape key closes any open modal
      $(document).on('keydown', function(e){
        if (e.key === "Escape") {
          $('.history-modal').each(function(){
            if (!$(this).hasClass('hidden')) {
              $(this).addClass('hidden').removeClass('flex');
            }
          });
          $('body').css('overflow', '');
        }
      });
    });
  </script>
</x-app-layout>
