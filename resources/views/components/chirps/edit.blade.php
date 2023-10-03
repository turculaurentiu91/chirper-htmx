@props(['chirp'])

<div class="chirp-edit" hx-target="this" hx-swap="outerHTML">
    <form class="p-6 flex flex-col space-x-2" hx-patch="{{ route('chirps.update', $chirp) }}">
        @csrf
        <textarea
            name="message"
            class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
        >{{ old('message', $chirp->message) }}</textarea>
        <x-input-error :messages="$errors->get('message')" class="mt-2" />
        <div class="mt-4 space-x-2">
            <x-primary-button>{{ __('Save') }}</x-primary-button>
            <a class="cursor-pointer" hx-get="{{ route('chirps.show', $chirp) }}"
            >{{ __('Cancel') }}</a>
        </div>
    </form>
</div>

