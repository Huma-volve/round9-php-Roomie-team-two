<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Services\SearchServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    private $searchServices;
    public function __construct(SearchServices $searchServices)
    {
        $this->searchServices = $searchServices;
    }
    public function search(Request $request)
    {
        try {
            $result = $this->searchServices->search($request);

            return response()->json([
                'message' => 'Rooms filtered successfully',
                'data' => [
                    'current_page' => $result->currentPage(),
                    'per_page' => $result->perPage(),
                    'total' => $result->total(),
                    'last_page' => $result->lastPage(),
                    'items' => $result->items(),
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error("Search Error: " . $e->getMessage());

            return response()->json([
                'message' => 'Something went wrong while filtering rooms.'
            ], 500);
        }
    }
}
