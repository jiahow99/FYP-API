<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateStoreRequest;
use App\Http\Requests\StoreUpdateTokenRequest;
use App\Http\Resources\StoreResource;
use App\Http\Resources\StoresCollectionResource;
use App\Models\Mechanic;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;


class StoreController extends Controller
{
    /**
     * Get all stores.
     */
    public function index(){
        $stores = Store::with('image')->get();
        return response()->json($stores);
    }

    /**
     * Get all stores.
     */
    public function create(CreateStoreRequest $request){

        $store = Store::create([
            'place_id' => $request->place_id,
            'uid' => $request->uid,
            'name' => $request->name,
            'slug' => preg_replace('/[^a-z0-9]+/', '-', strtolower($request->name)),
            'email' => $request->email,
            'address' => $request->address,
            'token' => $request->token,
        ]); 

        $mechanic = Mechanic::create([
            'store_id' => $store->id,
            'name' => $request->mechanic_name,
            'contact' => $request->mechanic_phone
        ]);

        $store['mechanic'] = $mechanic;

        return response()->json($store);
    }

    /**
     * Get specific stores.
     */
    public function showByUid(string $uid){
        $store = Store::where('uid', $uid)->first();

        if ($store) {
            // Mechanic details
            $store->load('mechanic');
            // Get mechanic photos url
            $placeDetailsUrl = "https://maps.googleapis.com/maps/api/place/details/json";
            $response = Http::get($placeDetailsUrl, [
                'place_id' => $store->place_id,
                'key' => env('GOOGLE_API_KEY')
            ]);
            // Success
            if ($response->successful()) 
            {
                // Update "photo_reference" field
                $store['photo_reference'] = $response->json('result.photos.0.photo_reference');
            }

            return response()->json($store);
        }

        // Not found
        return response()->json(['message'=>'Store is not registered.'], 404);
    }

    /**
     * Get specific stores.
     */
    public function showByPlaceId(string $placeId){
        $store = Store::where('place_id', $placeId)->first();

        if ($store) {
            $store->load('mechanic');
            return response()->json($store);
        }

        // Not found
        return response()->json(['message'=>'Store is not registered.'], 404);
    }

    /**
     * Update token
     */
    public function updateToken(StoreUpdateTokenRequest $request, string $uid){
        // Find store by uid
        $store = Store::where('uid', $uid)->first();
        // Update token field
        if ($store) {
            
            $store->update([
                'token' => $request->token
            ]);
            return response()->json($store);
        }
        // Not found
        return response()->json('Store not found', 404);
    }
    
    /**
     * Verify registed store
     * Add "token" field to data if registered
     */
    public function verifyStores(Request $request) {
        // Stores from request
        $stores = $request->stores;
        // Get verified stores
        $verifiedStores = Store::all();
        $verifiedPlaceId = $verifiedStores->pluck('place_id')->toArray();
        // Check if the store's place_id is in our database
        // If yes, then add 'is_registered' and 'token'
        foreach ($stores as &$store) {
            if (in_array($store['place_id'], $verifiedPlaceId)) {
                $matchingStore = $verifiedStores->firstWhere('place_id', $store['place_id']);
                $store['token'] = $matchingStore->token;
                $store['is_registered'] = true;
            } else {
                $store['is_registered'] = false;
            }
        }
        // Sort by "is_registered"
        $sortedStores = collect($stores)->sortByDesc('is_registered');
        return response()->json($sortedStores);
    }

}
