<!-- resources/views/user/submissions/edit.blade.php -->
<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Edit Submission') }}
    </h2>
  </x-slot>

  <div class="py-6">
    <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
      @if(session('success'))
        <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded mb-4">
          {{ session('success') }}
        </div>
      @endif

      <div class="bg-white shadow-sm sm:rounded-lg p-6">
        <form 
          method="POST" 
          action="{{ route('user.submissions.update', $submission) }}"
          enctype="multipart/form-data"
        >
          @csrf
          @method('PUT')

          {{-- Vendor Information --}}
          <fieldset class="mb-6 border rounded p-4">
            <legend class="font-semibold">{{ __('Vendor Information') }}</legend>
            <div class="grid gap-6">
              @php
                $vendorFields = [
                  'vendor_name'      => 'Vendor Name',
                  'schema_group'     => ['Schema Group', ['EMANL Import', 'EMANL Local']],
                  'account_group'    => ['Account Group', ['Affiliate','Third Party Local','Third Party Import']],
                  'beneficiary_tin'  => 'Beneficiary TIN',
                  'fax'              => 'Bank Account Name',
                  'bank_name'        => 'Bank',
                  'bank_account_no'  => 'Bank Account No',
                  'vendor_email'     => 'Vendor E‑mail',
                  'region'           => 'Region',
                  'purchasing_group' => ['Purchasing Group', ['Stock Material','Non-Stock Material','Services']],
                  'term_of_payment'  => ['Term of Payment', [
                    'Cash','Within 7 Days Due net','Within 14 Days Due net','Within 15 Days Due net',
                    'Within 21 Days Due net','Within 30 Days Due net','Within 35 Days Due net',
                    'Within 60 Days Due net','Within 90 Days Due net','Within 120 Days Due net'
                  ]],
                ];
              @endphp

              @foreach($vendorFields as $field => $info)
                <div>
                  <label class="block text-sm font-medium text-gray-700">{{ is_array($info) ? $info[0] : $info }}</label>

                  @if(is_array($info))
                    <div class="mt-1 space-y-2">
                      @foreach($info[1] as $opt)
                        <label class="inline-flex items-center mr-4">
                          <input
                            type="radio"
                            name="{{ $field }}"
                            value="{{ $opt }}"
                            {{ old($field, $submission->$field) == $opt ? 'checked' : '' }}
                            class="form-radio"
                          >
                          <span class="ml-2">{{ $opt }}</span>
                        </label>
                      @endforeach
                    </div>
                  @else
                    <input
                      name="{{ $field }}"
                      value="{{ old($field, $submission->$field) }}"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                  @endif
                  @error($field)
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                  @enderror

                  {{-- Approval status --}}
                  @php
                    $flag = $submission->finance_at
                      ? $submission->{"finance_{$field}_ok"}
                      : $submission->{"audit_{$field}_ok"};
                  @endphp
                  <label class="inline-flex items-center mt-2 text-sm text-gray-600">
                    <input
                      type="checkbox"
                      disabled
                      {{ $flag ? 'checked' : '' }}
                      class="opacity-50 cursor-not-allowed"
                    >
                    <span class="ml-2">{{ $flag ? 'Approved' : 'Disapproved' }}</span>
                  </label>
                </div>
              @endforeach
            </div>
          </fieldset>

          {{-- Contact Person --}}
          <fieldset class="mb-6 border rounded p-4">
            <legend class="font-semibold">{{ __('Contact Person') }}</legend>
            <div class="grid gap-6">
              @php
                $contactFields = [
                  'contact_title'              => ['Title', ['Mr','Mrs','Miss']],
                  'contact_gender'             => ['Gender', ['Male','Female']],
                  'contact_first_name'         => 'First Name',
                  'contact_last_name'          => 'Last Name',
                  'contact_address'            => 'Address',
                  'contact_telephone_primary'  => 'Telephone (primary)',
                  'contact_telephone_secondary'=> 'Telephone (secondary)',
                ];
              @endphp

              @foreach($contactFields as $field => $info)
                <div>
                  <label class="block text-sm font-medium text-gray-700">{{ is_array($info) ? $info[0] : $info }}</label>

                  @if(is_array($info))
                    <div class="mt-1 space-y-2">
                      @foreach($info[1] as $opt)
                        <label class="inline-flex items-center mr-4">
                          <input
                            type="radio"
                            name="{{ $field }}"
                            value="{{ $opt }}"
                            {{ old($field, $submission->$field) == $opt ? 'checked' : '' }}
                            class="form-radio"
                          >
                          <span class="ml-2">{{ $opt }}</span>
                        </label>
                      @endforeach
                    </div>
                  @else
                    <input
                      name="{{ $field }}"
                      value="{{ old($field, $submission->$field) }}"
                      class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                    >
                  @endif
                  @error($field)
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                  @enderror

                  {{-- Approval status --}}
                  @php
                    $flag = $submission->finance_at
                      ? $submission->{"finance_{$field}_ok"}
                      : $submission->{"audit_{$field}_ok"};
                  @endphp
                  <label class="inline-flex items-center mt-2 text-sm text-gray-600">
                    <input
                      type="checkbox"
                      disabled
                      {{ $flag ? 'checked' : '' }}
                      class="opacity-50 cursor-not-allowed"
                    >
                    <span class="ml-2">{{ $flag ? 'Approved' : 'Disapproved' }}</span>
                  </label>
                </div>
              @endforeach
            </div>
          </fieldset>

          {{-- Existing Document --}}
          @if($submission->document_path)
            <div class="mb-6">
              <label class="block text-sm font-medium text-gray-700">Existing Document</label>
              <a 
                href="{{ asset('storage/' . $submission->document_path) }}" 
                target="_blank"
                class="text-indigo-600 hover:text-indigo-900 mt-1 block"
              >
                Download / View
              </a>

              @php
                $flag = $submission->finance_at
                  ? $submission->finance_document_ok
                  : $submission->audit_document_ok;
              @endphp
              <label class="inline-flex items-center mt-2 text-sm text-gray-600">
                <input
                  type="checkbox"
                  disabled
                  {{ $flag ? 'checked' : '' }}
                  class="opacity-50 cursor-not-allowed"
                >
                <span class="ml-2">{{ $flag ? 'Approved' : 'Disapproved' }}</span>
              </label>
            </div>
          @endif

          {{-- Replace Document --}}
          <div class="mb-6">
            <label for="document" class="block text-sm font-medium text-gray-700">Replace Document (optional)</label>
            <input
              id="document"
              name="document"
              type="file"
              class="mt-1 block w-full text-sm text-gray-900
                     border border-gray-300 rounded-md cursor-pointer
                     focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
            >
            @error('document')
              <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div>

          {{-- Actions --}}
          <div class="flex justify-end space-x-2">
            <a href="{{ route('user.submissions.returned') }}"
               class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100">
              Back
            </a>
            <button
              type="submit"
              class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100"
            >
              Resubmit for Audit
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
