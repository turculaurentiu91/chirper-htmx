<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $chirps = Chirp::with('user')->latest()->paginate(25);

        return \response()->view('chirps.index', [
            'chirps' => $chirps
        ]);
    }

    public function pool(Request $request): Response
    {
        $validated = $request->validate([
            'latest_from' => 'required|date',
        ]);

        $chirps = Chirp::with('user')
            ->where('created_at', '>', $validated['latest_from'])
            ->where('user_id', '!=', $request->user()->id)
            ->latest()
            ->get();

        if($chirps->count() === 0) {
            return \response()->noContent();
        }

        return \response()->view('chirps.pool', [
            'chirps' => $chirps,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse|Response
    {
        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp = $request->user()->chirps()->create($validated);

        if($request->header('HX-Request')) {
            return response()->view('components.chirps.single', [
                'chirp' => $chirp,
            ]);
        }

        return redirect()->route('chirps.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Chirp $chirp): Response
    {
        return response()->view('components.chirps.single', [
            'chirp' => $chirp,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Chirp $chirp): Response
    {
        $this->authorize('update', $chirp);

        if($request->header('HX-Request')) {
            return response()->view('components.chirps.edit', [
                'chirp' => $chirp,
            ]);
        }

        return response()->view('chirps.edit', [
            'chirp' => $chirp,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chirp $chirp): RedirectResponse|Response
    {
        $this->authorize('update', $chirp);

        $validated = $request->validate([
            'message' => 'required|string|max:255',
        ]);

        $chirp->update($validated);

        if($request->header('HX-Request')) {
            return response()->view('components.chirps.single', [
                'chirp' => $chirp,
            ]);
        }

        return redirect(route('chirps.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Chirp $chirp): RedirectResponse|string
    {
        $this->authorize('delete', $chirp);

        $chirp->delete();

        if($request->header('HX-Request')) {
            return '';
        }

        return redirect(route('chirps.index'));
    }
}
