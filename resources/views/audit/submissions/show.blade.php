<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Review Submission') }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-4">

      <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <p><strong>User:</strong> {{ $submission->user->name }}</p>
        <p><strong>Submitted on:</strong> {{ $submission->created_at->format('M d, Y H:i') }}</p>
        <hr class="my-4">

        <form method="POST" action="{{ route('audit.submissions.update', $submission) }}">
          @csrf

          <!-- Title Check -->
          <div class="mb-4">
            {{-- ensure a value is always sent, even if unchecked --}}
            <input type="hidden" name="title_ok" value="0">
            <label class="inline-flex items-center">
              <input 
                type="checkbox" 
                name="title_ok" 
                value="1"
                {{-- repopulate on validation error, default to checked --}}
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
            {{-- hidden fallback for description_ok --}}
            <input type="hidden" name="description_ok" value="0">
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

          <!-- Auditor Comment -->
<div class="mb-4">
  <label for="audit_comment" class="block text-sm font-medium text-gray-700">
    Comment (optional)
  </label>
  <textarea
    id="audit_comment"
    name="audit_comment"
    rows="4"
    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
    placeholder="Anything the user should fix…"
  >{{ old('audit_comment', $submission->audit_comment) }}</textarea>
  @error('audit_comment')
    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
  @enderror
</div>


          <!-- Submit Decision -->
          <div class="flex justify-end space-x-2">
            <a href="{{ route('audit.submissions.index') }}"
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
