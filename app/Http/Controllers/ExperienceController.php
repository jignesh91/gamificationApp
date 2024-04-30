<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Experience;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ExperienceController extends Controller
{
    /**
     * Award experience points to a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function awardExperience(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id', // Validate that the user exists
            'points' => 'required|integer|min:1',  // Validate that points are positive integers
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $user = User::findOrFail($request->input('user_id')); // Check if the user exists

            // Check if the authenticated user has permission to award experience to the user
            if ($user->tenant_id != auth()->user()->tenant_id) {
                return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
            }

            $experience = new Experience([
                'user_id' => $user->id,
                'points' => $request->input('points'),
            ]);

            $experience->save();

            return response()->json([
                'message' => 'Experience points awarded successfully',
                'data' => $experience,
            ], Response::HTTP_OK);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found'], Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to award experience points'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
