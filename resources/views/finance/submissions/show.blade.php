<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Finance Review') }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">

      <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <p><strong>User:</strong> {{ $submission->user->name }}
        <span class="text-gray-600">({{ $submission->user->email }})</span>
      </p>
        <p><strong>Submitted on:</strong> {{ $submission->created_at->format('M d, Y H:i') }}</p>
        <hr class="my-4">

        <p><strong>Auditor:</strong> {{ $submission->auditor->name ?? '—' }}
        <span class="text-gray-600">
    ({{ optional($submission->auditor)->email ?? '—' }})
  </span>
      </p>
      <p><strong>Submitted on:</strong> 
  {{ $submission->audited_at ? $submission->audited_at->format('M d, Y H:i') : '—' }}
</p>


        <hr class="my-4">

        <form method="POST" action="{{ route('finance.submissions.update', $submission) }}">
          @csrf

          {{-- hidden fallbacks --}}
          <input type="hidden" name="title_ok" value="0">
          <input type="hidden" name="description_ok" value="0">

          <!-- Title Check -->
          <div class="mb-4">
            <label class="inline-flex items-center">
              <input 
                type="checkbox" 
                name="title_ok" 
                value="1"
                {{ old('title_ok', 1) ? 'checked' : '' }}
              >
              <span class="ml-2">Title is correct: “{{ $submission->title }}”</span>
            </label>
            @error('title_ok')
              <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          <!-- Description Check -->
          <div class="mb-4">
            <label class="inline-flex items-center">
              <input 
                type="checkbox" 
                name="description_ok" 
                value="1"
                {{ old('description_ok', 1) ? 'checked' : '' }}
              >
              <span class="ml-2">Description is correct.</span>
            </label>
            <p class="mt-1 p-2 border rounded bg-gray-50">{{ $submission->description }}</p>
            @error('description_ok')
              <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

            <!-- Finance Comment -->
      <div class="mb-4">
        <label for="finance_comment" class="block text-sm font-medium text-gray-700">
          Finance Comment (optional)
        </label>
        <textarea
          id="finance_comment"
          name="finance_comment"
          rows="4"
          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
          placeholder="Any notes for the user…"
        >{{ old('finance_comment', $submission->finance_comment) }}</textarea>
        @error('finance_comment')
          <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
        @enderror
      </div>

          <!-- Decision Buttons -->
          <div class="flex justify-end space-x-2">
            <a href="{{ route('finance.submissions.index') }}"
               class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">Cancel</a>
            <button type="submit"
            class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
              Submit Review
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</x-app-layout>
