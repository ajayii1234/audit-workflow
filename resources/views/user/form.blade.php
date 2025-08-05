<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
      {{ __('Submit a New Form') }}
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
        <form method="POST" action="{{ route('user.form.store') }}" enctype="multipart/form-data">
          @csrf

          <!-- Title -->
          <!-- <div class="mb-4">
            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
            <input
              id="title"
              name="title"
              type="text"
              value="{{ old('title') }}"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              required
            >
            @error('title')
              <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div> -->

          <!-- Description -->
          <!-- <div class="mb-4">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
              id="description"
              name="description"
              rows="4"
              class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
              required
            >{{ old('description') }}</textarea>
            @error('description')
              <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
          </div> -->
          

          <!-- Vendor Information -->
          <fieldset class="mb-6 border rounded p-4">
            <legend class="font-semibold">{{ __('Vendor Information') }}</legend>
            <div class="grid gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Vendor Name</label>
                <input
                  name="vendor_name"
                  value="{{ old('vendor_name') }}"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                >
                @error('vendor_name')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Schema Group</label>
                <label class="inline-flex items-center mr-4">
                  <input type="radio" name="schema_group" value="EMANL Import" {{ old('schema_group')=='EMANL Import'?'checked':'' }}>
                  <span class="ml-2">EMANL Import</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="radio" name="schema_group" value="EMANL Local" {{ old('schema_group')=='EMANL Local'?'checked':'' }}>
                  <span class="ml-2">EMANL Local</span>
                </label>
                @error('schema_group')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Account Group</label>
                <label class="inline-flex items-center mr-4">
                  <input type="radio" name="account_group" value="Affiliate" {{ old('account_group')=='Affiliate'?'checked':'' }}>
                  <span class="ml-2">Affiliate</span>
                </label>
                <label class="inline-flex items-center mr-4">
                  <input type="radio" name="account_group" value="Third Party Local" {{ old('account_group')=='Third Party Local'?'checked':'' }}>
                  <span class="ml-2">Third Party Local</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="radio" name="account_group" value="Third Party Import" {{ old('account_group')=='Third Party Import'?'checked':'' }}>
                  <span class="ml-2">Third Party Import</span>
                </label>
                @error('account_group')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Beneficiary TIN</label>
                <input
                  name="beneficiary_tin"
                  value="{{ old('beneficiary_tin') }}"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                >
                @error('beneficiary_tin')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Bank Account Name</label>
                <input
                  name="fax"
                  value="{{ old('fax') }}"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                >
                @error('fax')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Bank</label>
                <input
                  name="bank_name"
                  value="{{ old('bank_name') }}"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                >
                @error('bank_name')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Bank Account No</label>
                <input
                  name="bank_account_no"
                  value="{{ old('bank_account_no') }}"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                >
                @error('bank_account_no')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">E‑mail</label>
                <input
                  type="email"
                  name="vendor_email"
                  value="{{ old('vendor_email') }}"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                >
                @error('vendor_email')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Region</label>
                <input
                  name="region"
                  value="{{ old('region') }}"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                >
                @error('region')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Purchasing Group</label>
                <label class="inline-flex items-center mr-4">
                  <input type="radio" name="purchasing_group" value="Stock Material" {{ old('purchasing_group')=='Stock Material'?'checked':'' }}>
                  <span class="ml-2">Stock Material</span>
                </label>
                <label class="inline-flex items-center mr-4">
                  <input type="radio" name="purchasing_group" value="Non-Stock Material" {{ old('purchasing_group')=='Non-Stock Material'?'checked':'' }}>
                  <span class="ml-2">Non‑Stock Material</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="radio" name="purchasing_group" value="Services" {{ old('purchasing_group')=='Services'?'checked':'' }}>
                  <span class="ml-2">Services</span>
                </label>
                @error('purchasing_group')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Term of Payment</label>
                @foreach([
                  'Cash','Within 7 Days Due net','Within 14 Days Due net','Within 15 Days Due net',
                  'Within 21 Days Due net','Within 30 Days Due net','Within 35 Days Due net',
                  'Within 60 Days Due net','Within 90 Days Due net','Within 120 Days Due net'
                ] as $term)
                  <label class="inline-flex items-center mr-4">
                    <input type="radio" name="term_of_payment" value="{{ $term }}" {{ old('term_of_payment')==$term?'checked':'' }}>
                    <span class="ml-2">{{ $term }}</span>
                  </label>
                @endforeach
                @error('term_of_payment')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </fieldset>

          <!-- Contact Person -->
          <fieldset class="mb-6 border rounded p-4">
            <legend class="font-semibold">{{ __('Contact Person') }}</legend>
            <div class="grid gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700">Title</label>
                <label class="inline-flex items-center mr-4">
                  <input type="radio" name="contact_title" value="Mr" {{ old('contact_title')=='Mr'?'checked':'' }}>
                  <span class="ml-2">Mr</span>
                </label>
                <label class="inline-flex items-center mr-4">
                  <input type="radio" name="contact_title" value="Mrs" {{ old('contact_title')=='Mrs'?'checked':'' }}>
                  <span class="ml-2">Mrs</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="radio" name="contact_title" value="Miss" {{ old('contact_title')=='Miss'?'checked':'' }}>
                  <span class="ml-2">Miss</span>
                </label>
                @error('contact_title')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Gender</label>
                <label class="inline-flex items-center mr-4">
                  <input type="radio" name="contact_gender" value="Male" {{ old('contact_gender')=='Male'?'checked':'' }}>
                  <span class="ml-2">Male</span>
                </label>
                <label class="inline-flex items-center">
                  <input type="radio" name="contact_gender" value="Female" {{ old('contact_gender')=='Female'?'checked':'' }}>
                  <span class="ml-2">Female</span>
                </label>
                @error('contact_gender')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">First Name</label>
                <input
                  name="contact_first_name"
                  value="{{ old('contact_first_name') }}"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                >
                @error('contact_first_name')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Last Name</label>
                <input
                  name="contact_last_name"
                  value="{{ old('contact_last_name') }}"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                >
                @error('contact_last_name')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Address</label>
                <textarea
                  name="contact_address"
                  rows="3"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                >{{ old('contact_address') }}</textarea>
                @error('contact_address')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Telephone (primary)</label>
                <input
                  name="contact_telephone_primary"
                  value="{{ old('contact_telephone_primary') }}"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                >
                @error('contact_telephone_primary')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Telephone (secondary)</label>
                <input
                  name="contact_telephone_secondary"
                  value="{{ old('contact_telephone_secondary') }}"
                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                >
                @error('contact_telephone_secondary')
                  <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
              </div>
            </div>
          </fieldset>

          <!-- Document Upload -->
          <div class="mb-4">
            <label for="document" class="block text-sm font-medium text-gray-700">Upload Supporting Document (optional)</label>
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

          <!-- Submit -->
          <div class="flex justify-end">
            <button
              type="submit"
              class="px-4 py-2 border rounded text-gray-700 hover:bg-gray-100"
            >
              Submit for Audit
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</x-app-layout>
