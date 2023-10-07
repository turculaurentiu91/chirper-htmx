<div hx-get="{{ route('chirps.pool', ['latest_from' => $chirps->first()->created_at->toISOString()]) }}"
     hx-trigger="every 2s"
     hx-swap="outerHTML"></div>

<div class="border-transparent opacity-0"
     _="on intersection(intersecting) if intersecting remove me else remove .opacity-0" >
    <div class="fixed top-4 left-0 right-0 flex justify-center">
        <button
            _="on click window.scrollTo({ top: 0, behavior: 'smooth' })"
            type="button"
            class="py-2 px-4 bg-indigo-600 hover:bg-indigo-800 rounded-full text-white shadow-lg flex justify-center items-center gap-4"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0l-6.75 6.75M12 4.5l6.75 6.75" />
            </svg>

            New Chirps
        </button>
    </div>
</div>

@foreach($chirps as $chirp)
    <x-chirps.single :chirp="$chirp" />
@endforeach
