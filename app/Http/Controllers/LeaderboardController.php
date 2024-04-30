<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Experience;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LeaderboardController extends Controller
{
    /**
     * Display the top 10 users by experience points within a tenant.
     *
     * @param  int  $tenantId
     * @return \Illuminate\Http\Response
     */
    public function index($tenantId)
    {
        try {
            // Fetch the top 10 users by experience points for the specified tenant
            $users = User::where('tenant_id', $tenantId)
                        ->withSum('experiences', 'points') // Aggregate the sum of points from experiences
                        ->orderByDesc('experiences_sum_points') // Order by the sum of points
                        ->take(10) // Limit to top 10
                        ->get(['name', 'profile_image', 'experiences_sum_points']); // Select necessary fields

            return response()->json($users);
        } catch (ModelNotFoundException $e) {
            // Handle the case where the specified tenant is not found
            return response()->json(['error' => 'Tenant not found'], 404);
        } catch (\Exception $e) {
            // Handle other unexpected exceptions
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }
}
