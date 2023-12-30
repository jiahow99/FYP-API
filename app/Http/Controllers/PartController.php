<?php

namespace App\Http\Controllers;

use App\Http\Requests\FilterTyreRequest;
use App\Http\Resources\PartCollectionResource;
use App\Models\Category;
use App\Models\Part;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class PartController extends Controller
{
    /**
     * Get all spare parts.
     */
    public function index(Request $request) 
    {
        $parts = Part::with(['images', 'category', 'store'])
                    ->whereHas('category', function($query) {
                        $query->where('name', '!=', 'tyre');
                    })
                    ->filterPart($request)
                    ->get();

        return response()->json(PartCollectionResource::collection($parts));
    }

    /**
     * Get specific spare parts.
     */
    public function showPart(Part $part) 
    {
        $part->load(['images', 'category', 'store']);

        return response()->json($part);
    }

    /**
     * Get available options
     */
    public function allTyres(FilterTyreRequest $request)
    {
        $tyres = Category::where('slug','tyre')
            ->get()
            ->first()
            ->parts()
            ->with('images')
            ->filterTyre($request)
            ->get();
        
        // // Add "stock" field
        foreach($tyres as $tyre) {
            foreach($tyre->availableInchs as $availableInch) {
                $availableInch['stock'] = $availableInch->pivot->stock;
            }
        }

        return response()->json($tyres);
    }

    /**
     * Get available options
     */
    public function showTyre(Part $tyre)
    {
        $tyre->load(['images', 'category', 'store', 'availableInchs']);
        
        // Add "stock" field
        if ($tyre->availableInchs->isNotEmpty()) {
            foreach($tyre->availableInchs as $availableInch) 
            {
                $availableInch['stock'] = $availableInch->pivot->stock;
            }
        }

        return response()->json($tyre);
    }
}
