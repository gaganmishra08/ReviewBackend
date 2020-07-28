<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Location;

class AppApiController extends Controller
{
    public function __construct()
    {
        // $this->middleware('jwt.auth', ['except'=>['getTerms', 'getPrivacy', 'getFavoriteArticles']]);
        $this->middleware('jwt.auth', ['except'=>['getLocations']]);
    }
    public function getLocations(Request $request){
        $search = $request->input('search');
        $location = [];
        
        if($search){
            $location  = Location::where('city', 'like', '%'.$search.'%')->take(100)->get();
        }else{
            $location  = Location::take(100)->get();
        }
        return response()->json([
            'status' => true,
            'response' => $location
        ], 200);
    }
}
