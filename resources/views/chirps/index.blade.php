<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">
        <form
            method="POST"
            action="{{ route('chirps.store') }}"
            hx-post="{{ route('chirps.store') }}"
            hx-target="#chirps"
            hx-swap="afterbegin"
            hx-on="htmx:afterRequest: if(event.detail.successful) this.reset();"
        >
            @csrf
            <textarea
                name="message"
                placeholder="{{ __('What\'s on your mind?') }}"
                class="block w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
            >{{ old('message') }}</textarea>
            <x-input-error :messages="$errors->get('message')" class="mt-2" />
            <x-primary-button class="mt-4">{{ __('Chirp') }}</x-primary-button>
        </form>

        <div x-data="{noscriptFix: true}" id="chirps" class="mt-6 bg-white shadow-sm rounded-lg divide-y">
            <div hx-get="{{ route('chirps.pool', ['latest_from' => $chirps->first()->created_at->toISOString()]) }}"
                 hx-trigger="every 2s"
                 hx-swap="outerHTML"></div>

            @foreach ($chirps as $chirp)
                <x-chirps.single :chirp="$chirp" />
            @endforeach

            @if($chirps->nextPageUrl())
                        <div
                            hx-get="{{ $chirps->nextPageUrl() }}"
                            hx-select="#chirps>div"
                            hx-swap="outerHTML"
                            hx-trigger="intersect"
                            x-cloak
                            x-if="noscriptFix"
                        >
                            Loading more...
                        </div>
            @endif
        </div>
    </div>

    <noscript>
        <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8">
            {{ $chirps->links() }}
        </div>
    </noscript>
</x-app-layout>
