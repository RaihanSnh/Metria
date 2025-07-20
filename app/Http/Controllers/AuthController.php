<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Show register form
     */
    public function showRegister()
    {
        return view('auth.register');
    }

    /**
     * Handle login request
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(route('feed.index')); // Redirect to feed
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput();
    }

    /**
     * Handle register request
     */
    public function register(Request $request)
    {
        Log::info('Registration attempt started.', ['request' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'height_cm' => 'required|integer|min:100|max:250',
            'weight_kg' => 'required|numeric|min:30|max:200',
            'bust_circumference_cm' => 'nullable|integer|min:50|max:150',
            'waist_circumference_cm' => 'nullable|integer|min:50|max:150',
            'hip_circumference_cm' => 'nullable|integer|min:50|max:150',
        ]);

        if ($validator->fails()) {
            Log::error('Registration validation failed.', ['errors' => $validator->errors()]);
            return back()->withErrors($validator)->withInput();
        }

        try {
        $user = User::create([
            'username' => $request->username,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'height_cm' => $request->height_cm,
            'weight_kg' => $request->weight_kg,
            'bust_circumference_cm' => $request->bust_circumference_cm,
            'waist_circumference_cm' => $request->waist_circumference_cm,
            'hip_circumference_cm' => $request->hip_circumference_cm,
        ]);

        Auth::login($user);

            Log::info('User registered successfully.', ['user_id' => $user->id]);

        return redirect()->route('feed.index')->with('success', 'Registration successful! Welcome to Metria.'); // Redirect to feed
        } catch (\Exception $e) {
            Log::critical('User creation failed.', ['exception' => $e->getMessage()]);
            return back()->with('error', 'An unexpected error occurred. Please try again.')->withInput();
        }
    }

    /**
     * Handle logout request
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    /**
     * API Login
     */
    public function apiLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        }

        $user = Auth::user();
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ]);
    }

    /**
     * API Register
     */
    public function apiRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string|max:255|unique:users',
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::min(8)],
            'height_cm' => 'required|integer|min:100|max:250',
            'weight_kg' => 'required|numeric|min:30|max:200',
            'bust_circumference_cm' => 'nullable|integer|min:50|max:150',
            'waist_circumference_cm' => 'nullable|integer|min:50|max:150',
            'hip_circumference_cm' => 'nullable|integer|min:50|max:150',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'height_cm' => $request->height_cm,
            'weight_kg' => $request->weight_kg,
            'bust_circumference_cm' => $request->bust_circumference_cm,
            'waist_circumference_cm' => $request->waist_circumference_cm,
            'hip_circumference_cm' => $request->hip_circumference_cm,
        ]);

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'data' => [
                'user' => $user,
                'token' => $token,
            ],
        ], 201);
    }

    /**
     * API Logout
     */
    public function apiLogout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logout successful',
        ]);
    }
}
