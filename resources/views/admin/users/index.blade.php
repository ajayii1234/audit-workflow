<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('User Management') }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
      @if(session('success'))
        <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded">
          {{ session('success') }}
        </div>
      @endif

      <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Role</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Change Role</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Can Search?</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            @foreach($users as $user)
              @php
                // Use the first assigned role or default to 'user'
                $current = $user->roles->pluck('name')->first() ?: 'user';
              @endphp
              <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ ucfirst($current) }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <form 
                    method="POST" 
                    action="{{ route('admin.users.promote', $user) }}"
                    class="inline-flex items-center space-x-2"
                  >
                    @csrf

                    <select 
                      name="role" 
                      class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    >
                      @foreach($roles as $role)
                        <option 
                          value="{{ $role->name }}"
                          {{ $current === $role->name ? 'selected' : '' }}
                        >
                          {{ ucfirst($role->name) }}
                        </option>
                      @endforeach
                    </select>

                    <button 
                            type="submit" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
                          Change Role
                    </button>


                  </form>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
        @if($user->can_search)
          <span class="text-green-600 font-semibold">Yes</span>
        @else
          <span class="text-red-600 font-semibold">No</span>
        @endif
      </td>
      <td class="px-6 py-4 whitespace-nowrap text-sm">
        <form method="POST" action="{{ route('admin.users.toggle_search', $user) }}">
          @csrf
          <button 
            type="submit"
            class="px-2 py-1 border rounded text-sm 
                   {{ $user->can_search ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }}
                   hover:opacity-80"
          >
            {{ $user->can_search ? 'Revoke' : 'Grant' }}
          </button>
        </form>
      </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>
</x-app-layout>
