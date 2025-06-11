<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\User;
use Illuminate\Http\Request;

class EmailController extends Controller
{
    /**
     * List all emails for a user.
     */
    public function index(User $user)
    {
        return response()->json($user->emails, 200);
    }

    /**
     * Store a newly created email for a user.
     */
    public function store(Request $request, User $user)
    {
        $data = $request->validate([
            'email' => ['required', 'email', 'unique:emails,email'],
        ]);

        $email = $user->emails()->create($data);

        return response()->json($email, 201);
    }

    /**
     * Show a specific email.
     */
    public function show(User $user, Email $email)
    {
        if ($email->user_id !== $user->id) {
            return response()->json(['message' => 'Not found'], 404);
        }
        return response()->json($email, 200);
    }

    /**
     * Update a specific email.
     */
    public function update(Request $request, User $user, Email $email)
    {
        if ($email->user_id !== $user->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $data = $request->validate([
            'email' => ['required', 'email', 'unique:emails,email,' . $email->id],
        ]);

        $email->update($data);

        return response()->json($email, 200);
    }

    /**
     * Delete a specific email.
     */
    public function destroy(User $user, Email $email)
    {
        if ($email->user_id !== $user->id) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $email->delete();

        return response()->json(['message' => 'Email deleted successfully'], 200);
    }
}
