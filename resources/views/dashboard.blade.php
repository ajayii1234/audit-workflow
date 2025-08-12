{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
  <!-- … header … -->

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          {{ __("You're logged in!") }}

          <div class="mt-4 space-x-2">
            @if(auth()->user()->hasRole('user'))
              <a href="{{ url('/user/form') }}"
                 class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                Go to User Form
              </a>

              <a href="{{ url('/user/submissions/returned') }}"
                 class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                Go to Corrections
              </a>

              <!-- New: My Submissions -->
              <a href="{{ route('user.submissions.index') }}"
                 class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                My Submissions
              </a>

              @if(auth()->user()->canSearch())
                <a href="{{ route('user.submissions.search') }}"
                   class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                  Search Submissions
                </a>
              @endif
            @endif
            
            @if(auth()->user()->hasRole('audit'))
              <a href="{{ url('/audit/submissions') }}"
                 class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                Go to Audit Page
              </a>
            @endif

            @if(auth()->user()->hasRole('finance'))
              <a href="{{ url('/finance/submissions') }}"
                 class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                Go to Finance Page
              </a>
            @endif

            {{-- IT role button --}}
            @if(auth()->user()->hasRole('it'))
              <a href="{{ url('/it/submissions') }}"
                 class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                Go to IT Page
              </a>

                <!-- New: View forms already submitted to SAP -->
                <a href="{{ route('it.submissions.submitted') }}"
                 class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                Submitted to SAP
              </a>
            @endif

            @if(auth()->user()->hasRole('admin'))
              <a href="{{ url('/admin/users') }}"
                 class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                Change Roles
              </a>

              <a href="{{ route('it.submissions.submitted') }}"
                 class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                Submitted to SAP
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</x-app-layout>
