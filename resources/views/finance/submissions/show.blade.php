<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Finance Review') }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-4">

      <div class="bg-white shadow-sm sm:rounded-lg p-6">
        {{-- User + Submitted --}}
        <p><strong>User:</strong> {{ $submission->user->name }}
          <span class="text-gray-600">({{ $submission->user->email }})</span>
        </p>
        <p><strong>Submitted on:</strong>
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
          {{ $submission->audited_at
              ? $submission->audited_at->format('M d, Y H:i')
              : '—' }}
        </p>
        <hr class="my-4">

        <form method="POST" action="{{ route('finance.submissions.update', $submission) }}">
          @csrf

          {{-- Hidden fallbacks --}}
          <input type="hidden" name="title_ok" value="0">
          <input type="hidden" name="description_ok" value="0">
          <input type="hidden" name="document_ok" value="0">
          @foreach([
            'vendor_name','schema_group','account_group','beneficiary_tin',
            'fax','bank_name','bank_account_no','vendor_email','region',
            'purchasing_group','term_of_payment',
            'contact_title','contact_gender','contact_first_name',
            'contact_last_name','contact_address','contact_telephone_primary',
            'contact_telephone_secondary'
          ] as $field)
            <input type="hidden" name="{{ $field }}_ok" value="0">
          @endforeach

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
              <p class="mb-2">{{ $submission->$field }}</p>
              <label class="inline-flex items-center">
                <input
                  type="checkbox"
                  name="{{ $field }}_ok"
                  value="1"
                  {{ old($field.'_ok', $submission->{'finance_'.$field.'_ok'} ?? 1) ? 'checked' : '' }}
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
              <p class="mb-2">{{ $submission->$field }}</p>
              <label class="inline-flex items-center">
                <input
                  type="checkbox"
                  name="{{ $field }}_ok"
                  value="1"
                  {{ old($field.'_ok', $submission->{'finance_'.$field.'_ok'} ?? 1) ? 'checked' : '' }}
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
            <label class="inline-flex items-center">
              <input
                type="checkbox"
                name="document_ok"
                value="1"
                {{ old('document_ok', $submission->finance_document_ok ?? 1) ? 'checked' : '' }}
              >
              <span class="ml-2">Approve Document</span>
            </label>
            @error('document_ok')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
          </div>

          {{-- Finance Comment --}}
          <div class="mb-6">
            <label for="finance_comment" class="block text-sm font-medium text-gray-700">
              Finance Comment (optional)
            </label>
            <textarea
              id="finance_comment"
              name="finance_comment"
              rows="4"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              placeholder="Optional notes…"
            >{{ old('finance_comment', $submission->finance_comment) }}</textarea>
            @error('finance_comment')<p class="text-red-600 text-sm mt-1">{{ $message }}</p>@enderror
          </div>

          {{-- Actions --}}
          <div class="flex justify-end space-x-2">
            <a href="{{ route('finance.submissions.index') }}"
               class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
              Cancel
            </a>
            <button type="submit" class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
              Submit Review
            </button>
          </div>
        </form>
      </div>

    </div>
  </div>
</x-app-layout>
