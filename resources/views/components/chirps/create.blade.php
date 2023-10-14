@props(['oob' => true])

<form
    id="chirps-create"
    @if($oob)
        hx-swap-oob="true"
    @endif
    method="POST"
    action="{{ route('chirps.store') }}"
    hx-post="{{ route('chirps.store') }}"
    hx-target="#chirps"
    hx-swap="afterbegin"
>
    @csrf
    <textarea
        aria-label="message"
        name="message"
        placeholder="{{ __('What\'s on your mind?') }}"
        class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
    >{{ old('message') }}</textarea>
    <x-input-error :messages="$errors->get('message')" class="mt-2" />
    <x-primary-button class="mt-4">{{ __('Chirp') }}</x-primary-button>
</form>
