<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // Validate the incoming request data
            $validator = Validator::make($request->all(), [
                'tenant_id' => 'required|exists:tenants,id',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'pronoun' => 'required|string|max:10',
                'instagram_handle' => 'nullable|string|max:255',
                'profile_image' => 'nullable|image|max:2048',
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator); // Throw a ValidationException if validation fails
            }

            // Store profile image if provided
            $path = null;
            if ($request->hasFile('profile_image')) {
                $path = $request->file('profile_image')->store('profile_images', 'public'); // Store image in 'storage/app/public/profile_images'
            }

            // Create a new user record
            $user = User::create([
                'tenant_id' => $request->tenant_id,
                'name' => $request->name,
                'email' => $request->email,
                'pronoun' => $request->pronoun,
                'instagram_handle' => $request->instagram_handle,
                'profile_image' => $path,
                'password' => Hash::make($request->password),
            ]);

            // Generate token for the user
            $token = $user->createToken('api-token')->plainTextToken;

            // Fire the registered event
            event(new Registered($user));

            // Log in the user
            Auth::login($user);

            // Return user data and token in the response
            return response()->json(['user' => $user, 'token' => $token], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            // Handle unexpected exceptions
            return response()->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}