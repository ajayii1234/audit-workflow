<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Review Submission') }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

      <div class="bg-white shadow-sm sm:rounded-lg p-6">
        {{-- User + Submitted at --}}
        <p><strong>User:</strong> {{ $submission->user->name }}</p>
        <p><strong>Submitted on:</strong> {{ $submission->created_at->format('M d, Y H:i') }}</p>
        <hr class="my-4">

        <form method="POST" action="{{ route('audit.submissions.update', $submission) }}">
          @csrf

          {{-- Vendor Information --}}
          <fieldset class="mb-6 border rounded p-4">
            <legend class="font-semibold">{{ __('Vendor Information') }}</legend>
            
            @php
              $vendor = $submission;
            @endphp

            @foreach([
              'vendor_name'      => 'Vendor Name',
              'schema_group'     => 'Schema Group',
              'account_group'    => 'Account Group',
              'beneficiary_tin'  => 'Beneficiary TIN',
              'fax'              => 'Bank Account Name',
              'bank_name'        => 'Bank',
              'bank_account_no'  => 'Bank Account No',
              'vendor_email'     => 'Vendor E‑mail',
              'region'           => 'Region',
              'purchasing_group' => 'Purchasing Group',
              'term_of_payment'  => 'Term of Payment',
            ] as $field => $label)
            <div class="mb-4">
              <p class="font-medium">{{ $label }}</p>
              <p class="mb-2">{{ $vendor->$field }}</p>

              <input type="hidden" name="{{ $field }}_ok" value="0">
              <label class="inline-flex items-center">
                <input
                  type="checkbox"
                  name="{{ $field }}_ok"
                  value="1"
                  {{ old($field.'_ok', $submission->{'audit_'. $field .'_ok'} ?? 1) ? 'checked' : '' }}
                >
                <span class="ml-2">Approve {{ $label }}</span>
              </label>
              @error($field.'_ok')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            @endforeach
          </fieldset>

          {{-- Contact Person --}}
          <fieldset class="mb-6 border rounded p-4">
            <legend class="font-semibold">{{ __('Contact Person') }}</legend>
            
            @foreach([
              'contact_title'              => 'Contact Title',
              'contact_gender'             => 'Contact Gender',
              'contact_first_name'         => 'First Name',
              'contact_last_name'          => 'Last Name',
              'contact_address'            => 'Address',
              'contact_telephone_primary'  => 'Telephone (primary)',
              'contact_telephone_secondary'=> 'Telephone (secondary)',
            ] as $field => $label)
            <div class="mb-4">
              <p class="font-medium">{{ $label }}</p>
              <p class="mb-2">{{ $submission->$field }}</p>

              <input type="hidden" name="{{ $field }}_ok" value="0">
              <label class="inline-flex items-center">
                <input
                  type="checkbox"
                  name="{{ $field }}_ok"
                  value="1"
                  {{ old($field.'_ok', $submission->{'audit_'. $field .'_ok'} ?? 1) ? 'checked' : '' }}
                >
                <span class="ml-2">Approve {{ $label }}</span>
              </label>
              @error($field.'_ok')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
            </div>
            @endforeach
          </fieldset>

          {{-- Document --}}
          <div class="mb-6">
            @if($submission->document_path)
              <p class="font-medium">Uploaded Document</p>
              <a
                href="{{ asset('storage/' . $submission->document_path) }}"
                target="_blank"
                class="text-indigo-600 hover:text-indigo-900 mb-2 block"
              >Download / View</a>
            @endif

            <input type="hidden" name="document_ok" value="0">
            <label class="inline-flex items-center">
              <input
                type="checkbox"
                name="document_ok"
                value="1"
                {{ old('document_ok', $submission->audit_document_ok ?? 1) ? 'checked' : '' }}
              >
              <span class="ml-2">Approve Document</span>
            </label>
            @error('document_ok')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
          </div>

          {{-- Auditor Comment --}}
          <div class="mb-6">
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
            @error('audit_comment')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
          </div>

          {{-- Submit --}}
          <div class="flex justify-end space-x-2">
            <a href="{{ route('audit.submissions.index') }}"
               class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">Cancel</a>
            <button type="submit" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
              Submit Review
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</x-app-layout>
