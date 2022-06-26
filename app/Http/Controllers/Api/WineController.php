<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWineRequest;
use App\Http\Requests\UpdateWineRequest;
use App\Http\Resources\WineCollection;
use App\Http\Resources\WineResource;
use App\Models\Wine;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

class WineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return WineCollection
     */
    public function index(): WineCollection
    {
        
        $wines = Wine::query()

            ->when(request('name'), function ($query) {
                $query->where('name','LIKE', '%'. request('name'). '%');
            })
            ->when(request('color'), function ($query) {
                $query->where('color', request('color'));
            })
            ->when(request('provenance'), function ($query) {
                $query->where('provenance','LIKE', '%'.request('provenance').'%');
            })
            ->when(request('trade'), function ($query) {
                $query->where('trade','LIKE', '%'.request('trade').'%');
            })
            ->latest()
            ->simplePaginate(20);
        return new WineCollection($wines);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreWineRequest $request
     * @return WineResource
     */
    public function store(StoreWineRequest $request)
    {
        $validated = $request->validated();
        $validated['user_id'] = empty($validated['user_id']) ? auth()->id() : $validated['user_id'];
        if ($request->hasFile('images')) {
            if (is_iterable($request->file('images'))) {
                $validated['images']= [];
                foreach ($request->file('images') as $image) {
                    $validated['images'][] = $image->store('wines');
                }
                $validated['images'] = json_encode($validated['images']);
            } else {
                $validated['images'] = $request->file('images')->store('wines');
            }

        }
        $wine =  Wine::query()->create($validated);
        return new WineResource($wine);

    }

    /**
     * Display the specified resource.
     *
     * @param Wine $wine
     * @return WineResource
     */
    public function show(Wine $wine): WineResource
    {
        return new WineResource($wine);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateWineRequest $request
     * @param Wine $wine
     * @return WineResource
     */
    public function update(UpdateWineRequest $request, Wine $wine): WineResource
    {
        $validated = $request->validated();
        $validated['user_id'] = empty($validated['user_id']) ? auth()->id() : $validated['user_id'];
        if ($request->hasFile('images')) {
            foreach ($request->images as $image) {
                $validated['images'][] = $image->store('wines');
            }
        }
        $wine->update($validated);
        return new WineResource($wine);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Wine $wine
     * @return JsonResponse
     */
    public function destroy(Wine $wine): JsonResponse
    {
        $wine->delete();
        return response()->json(['message' => 'Wine deleted successfully']);
    }
}
