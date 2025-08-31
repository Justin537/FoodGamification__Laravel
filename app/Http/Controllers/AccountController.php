<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\XpHistory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use App\Notifications\AccountVerification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException; // Add this line
class AccountController extends Controller
{

    // GET /accounts
    public function index()
    {
        return Account::all();
    }

   public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            // 'captcha' => 'required|captcha', // Check against Laravel session
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $account = Account::where('email', $request->email)->first();

        if (!$account || !Hash::check($request->password, $account->password)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        return response()->json([
            'message' => 'Login successful',
            'account' => $account,
        ]);
    }

    public function verificationShow(Request $request)
    {
        $userJson = $request->input('user');
        $decoded = json_decode($userJson);

        if (!$decoded || !isset($decoded->id)) {
            return view('verification_error', ['message' => 'Invalid user data.']);
        }

        $account = Account::find($decoded->id);
        if (!$account) {
            return view('verification_error', ['message' => 'Account not found.']);
        }

        return view('verification_show', [
            'user' => $account // 👈 Pass as $user for template consistency
        ]);
    }



    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|unique:accounts,email',
                'password' => 'required|string',
                'name' => 'required|string',
                'gender' => 'required|string',
                'exp' => 'required|integer',
                'berat' => 'required|integer',
                'tinggi' => 'required|integer',
                'inventory' => 'nullable|string',
                'setting' => 'nullable|string',
            ]);

            $validated['password'] = Hash::make($validated['password']);

            $account = Account::create($validated);

            // Generate signed verification URL
            $verificationUrl = URL::temporarySignedRoute(
                'account.verify',
                now()->addMinutes(30),
                ['id' => $account->id]
            );

            // Send verification email
            $account->notify(new AccountVerification($verificationUrl));

            return response()->json([
                'status' => 'success',
                'message' => 'Account created. Please check your email to verify your account.',
                'account' => $account
            ], 201);

        } catch (ValidationException $e) {
            // Return validation errors as JSON with 422 status code
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            // Return generic error response
            return response()->json([
                'status' => 'error',
                'message' => 'Server error: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function verify(Request $request, $id)
    {
        try {
            // Find the Account using the ID
            $Account = Account::findOrFail($id);

            // Check if the email is already verified
            if ($Account->is_verify) {
                // Return already verified view
                return view('verification_already', ['id'=>$Account->id,'message' => 'Your email is already verified.']);
            }

            // Validate the signed URL (expiration check)
            if (! $request->hasValidSignature()) {
                // Show verification expired page if the link is invalid or expired
                return view('verification_expired', ['id'=>$Account->id,'message' => 'This verification link is invalid or has expired.']);
            }

            // If everything is good, mark the email as verified
            $Account->is_verify = true;
            // $Account->verified_at = now();
            $Account->save();

            // Return verification success view
            return view('verification_success', ['message' => 'Your email has been successfully verified.']);

        } catch (ModelNotFoundException $e) {
            // If Account is not found
            return view('verification_error', ['message' => 'Account not found.']);
        }
    }
    // PUT /accounts/{id}
    public function update(Request $request, $id)
    {
        $account = Account::findOrFail($id);

        $validated = $request->validate([
            'email' => 'sometimes|email|unique:accounts,email,' . $id,
            'name' => 'sometimes|string',
            'gender' => 'sometimes|string',
            'exp' => 'sometimes|integer',
            'berat' => 'sometimes|integer',
            'tinggi' => 'sometimes|integer',
            'inventory' => 'nullable|string',
            'setting' => 'nullable|string',
        ]);

        $account->update($validated);
        return response()->json($account);
    }

    // DELETE /accounts/{id}
    public function destroy($id)
    {
        $account = Account::findOrFail($id);
        $account->delete();
        return response()->json(['message' => 'Account deleted']);
    }
}
