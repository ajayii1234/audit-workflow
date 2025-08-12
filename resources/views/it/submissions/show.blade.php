{{-- resources/views/it/submissions/show.blade.php --}}
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      I.T. Review — {{ $submission->submission_reference }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

      <div class="bg-white shadow-sm sm:rounded-lg p-6">
        {{-- User + Submitted --}}
        <p>
          <strong>User:</strong> {{ $submission->user->name }}
          <span class="text-gray-600">({{ $submission->user->email }})</span>
        </p>
        <p>
          <strong>Submitted on:</strong>
          {{ $submission->created_at->format('M d, Y H:i') }}
        </p>

        <hr class="my-4">

        {{-- Audit summary --}}
        <p>
          <strong>Auditor:</strong> {{ optional($submission->auditor)->name ?? '—' }}
          <span class="text-gray-600">({{ optional($submission->auditor)->email ?? '—' }})</span>
        </p>
        <p>
          <strong>Audited on:</strong>
          {{ $submission->audited_at ? $submission->audited_at->format('M d, Y H:i') : '—' }}
        </p>

        <hr class="my-4">

        {{-- Finance summary --}}
        <p>
          <strong>Finance:</strong> {{ optional($submission->financier)->name ?? '—' }}
          <span class="text-gray-600">({{ optional($submission->financier)->email ?? '—' }})</span>
        </p>
        <p>
          <strong>Finance approved on:</strong>
          {{ $submission->finance_at ? $submission->finance_at->format('M d, Y H:i') : '—' }}
        </p>

        <hr class="my-4">

        {{-- Show finance comment if any --}}
        @if($submission->finance_comment)
          <div class="mb-4">
            <p class="font-medium">Finance Comment</p>
            <div class="text-sm text-gray-700 whitespace-pre-wrap">{{ $submission->finance_comment }}</div>
          </div>
        @endif

        {{-- Now render the same sections as finance view, but display values + whether finance approved them --}}
        <form method="POST" action="{{ route('it.submissions.update', $submission) }}">
          @csrf
          @method('PUT')

          {{-- Vendor Information --}}
          <fieldset class="mb-6 border rounded p-4">
            <legend class="font-semibold">{{ __('Vendor Information') }}</legend>

            @foreach([
              'vendor_name'      => 'Vendor Name',
              'schema_group'     => 'Schema Group',
              'account_group'    => 'Account Group',
              'beneficiary_tin'  => 'Beneficiary TIN',
              'fax'              => 'Bank Account Name',
              'bank_name'        => 'Bank',
              'bank_account_no'  => 'Bank Account No',
              'vendor_email'     => 'Vendor E-mail',
              'region'           => 'Region',
              'purchasing_group' => 'Purchasing Group',
              'term_of_payment'  => 'Term of Payment',
            ] as $field => $label)
              <div class="mb-4">
                <p class="font-medium">{{ $label }}</p>
                <p class="mb-2 text-sm text-gray-700">{{ $submission->$field ?? '—' }}</p>

                {{-- Show what Finance approved as a disabled checkbox --}}
                @php
                  // finance flags are named like finance_vendor_name_ok
                  $flagName = 'finance_' . $field . '_ok';
                  $flagValue = $submission->{$flagName} ?? false;
                @endphp

                <label class="inline-flex items-center">
                  <input type="checkbox" disabled {{ $flagValue ? 'checked' : '' }} class="form-checkbox">
                  <span class="ml-2">Finance approved {{ $label }}</span>
                </label>
              </div>
            @endforeach
          </fieldset>

          {{-- Contact Person --}}
          <fieldset class="mb-6 border rounded p-4">
            <legend class="font-semibold">{{ __('Contact Person') }}</legend>

            @foreach([
              'contact_title'              => 'Title',
              'contact_gender'             => 'Gender',
              'contact_first_name'         => 'First Name',
              'contact_last_name'          => 'Last Name',
              'contact_address'            => 'Address',
              'contact_telephone_primary'  => 'Telephone (primary)',
              'contact_telephone_secondary'=> 'Telephone (secondary)',
            ] as $field => $label)
              <div class="mb-4">
                <p class="font-medium">{{ $label }}</p>
                <p class="mb-2 text-sm text-gray-700">{{ $submission->$field ?? '—' }}</p>

                @php
                  $flagName = 'finance_' . $field . '_ok';
                  $flagValue = $submission->{$flagName} ?? false;
                @endphp

                <label class="inline-flex items-center">
                  <input type="checkbox" disabled {{ $flagValue ? 'checked' : '' }} class="form-checkbox">
                  <span class="ml-2">Finance approved {{ $label }}</span>
                </label>
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

            {{-- Show whether finance approved the document --}}
            <label class="inline-flex items-center">
              <input type="checkbox" disabled {{ $submission->finance_document_ok ? 'checked' : '' }} class="form-checkbox">
              <span class="ml-2">Finance approved Document</span>
            </label>
          </div>

          {{-- I.T. Action: Mark as submitted to SAP --}}
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700">I.T. — Submit to SAP</label>

            {{-- keep a fallback hidden input so unchecked becomes 0 --}}
            <input type="hidden" name="it_submitted" value="0">

            <label class="inline-flex items-center">
              <input type="checkbox" name="it_submitted" value="1"
                     {{ old('it_submitted', $submission->it_submitted ?? false) ? 'checked' : '' }}>
              <span class="ml-2">Mark as submitted to SAP</span>
            </label>

            @error('it_submitted')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
          </div>

          <div class="mb-6">
            <label for="it_comment" class="block text-sm font-medium text-gray-700">I.T. Comment (optional)</label>
            <textarea id="it_comment" name="it_comment" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('it_comment', $submission->it_comment) }}</textarea>
            @error('it_comment')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
          </div>

          {{-- Actions --}}
          <div class="flex justify-end space-x-2">
            <a href="{{ route('it.submissions.index') }}"
               class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
              Cancel
            </a>

            <button type="submit" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
              Save
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</x-app-layout>
