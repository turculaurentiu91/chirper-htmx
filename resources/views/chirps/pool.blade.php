<div hx-get="{{ route('chirps.pool', ['latest_from' => $chirps->first()->created_at->toISOString()]) }}"
     hx-trigger="every 2s"
     hx-swap="outerHTML"></div>

@foreach($chirps as $chirp)
    <x-chirps.single :chirp="$chirp" />
@endforeach
