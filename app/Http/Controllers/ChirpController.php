<?php

namespace App\Http\Controllers;

use App\Models\Chirp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class ChirpController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {

        $chirps = Chirp::with('user')
            ->latest()
            ->when($request->has('search'), fn($query) => $query->search($request->query('search')))
            ->paginate(25);

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
            ->when($request->has('search'), fn($query) => $query->search($request->query('search')))
            ->latest()
            ->get();

        if ($chirps->count() === 0) {
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
        try {
            $validated = $request->validate([
                'message' => 'required|string|max:255',
            ]);

            $chirp = $request->user()->chirps()->create($validated);

            if ($request->header('HX-Request')) {
                return response()->view('components.chirps.single', [
                    'chirp' => $chirp,
                    'withCreateForm' => true,
                ]);
            }

            return redirect()->route('chirps.index');
        } catch (ValidationException $e) {
            if (!$request->header('HX-Request')) {
                throw $e;
            }

            return response()->view('components.chirps.create', [
                'errors' => collect($e->errors()),
            ]);
        }
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

        if ($request->header('HX-Request')) {
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

        try {
            $validated = $request->validate([
                'message' => 'required|string|max:255',
            ]);

            $chirp->update($validated);

            if ($request->header('HX-Request')) {
                return response()->view('components.chirps.single', [
                    'chirp' => $chirp,
                ]);
            }

            return redirect(route('chirps.index'));
        } catch (ValidationException $e) {
            if (!$request->header('HX-Request')) {
                throw $e;
            }

            return response()->view('components.chirps.edit', [
                'chirp' => $chirp,
                'errors' => collect($e->errors()),
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Chirp $chirp): RedirectResponse|string
    {
        $this->authorize('delete', $chirp);

        $chirp->delete();

        if ($request->header('HX-Request')) {
            return '';
        }

        return redirect(route('chirps.index'));
    }

    public function confirmDestroy(Chirp $chirp): Response
    {
        return response()->view('components.chirps.confirm-destroy', [
            'chirp' => $chirp,
        ]);
    }
}
