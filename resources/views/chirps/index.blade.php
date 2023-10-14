<x-app-layout>
    <div class="max-w-2xl mx-auto p-4 sm:p-6 lg:p-8">

        <form
            method="GET"
            action="{{ route('chirps.index') }}"
            class="flex items-center space-x-2 mb-4 w-full bg-white border-1 border-gray-300 px-4 py-2 rounded-md shadow-sm focus-within:ring focus-within:border-indigo-200 focus-within:ring-indigo-200 focus-within:ring-opacity-50"

        >
            <input
                type="text"
                name="search"
                aria-label="search"
                class="w-full bg-white border-0 p-0 focus:ring-0 focus:border-0 focus:outline-none"
                placeholder="Search for chirps"
                value="{{ request('search') }}"
                hx-trigger="keyup changed delay:500ms"
                hx-get="{{ route('chirps.index') }}"
                hx-target="#chirps"
                hx-swap="outerHTML"
                hx-select="#chirps"
            >
            <button
                type="submit"
                class="bg-gray-100 p-1 rounded hover:bg-gray-400 transition"
            >
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                </svg>
            </button>
        </form>

        <x-chirps.create :oob="false"></x-chirps.create>

        <div x-data="{noscriptFix: true}" id="chirps" class="mt-6 bg-white shadow-sm rounded-lg divide-y">
            @if($chirps->count() > 0)
                <div hx-get="{{
                        route('chirps.pool', [
                            'latest_from' => $chirps->first()->created_at->toISOString(),
                            'search' => request('search')]) }}"
                     hx-trigger="every 2s"
                     hx-swap="outerHTML"></div>
            @endif

            @foreach ($chirps as $chirp)
                <x-chirps.single :chirp="$chirp" />
            @endforeach

            @if($chirps->nextPageUrl())
                        <div
                            hx-get="{{ $chirps->nextPageUrl() }}"
                            hx-select="#chirps>div.chirp,#chirps>div.chirps-paginator"
                            hx-swap="outerHTML"
                            hx-trigger="intersect"
                            x-cloak
                            x-if="noscriptFix"
                            class="chirps-paginator"
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
