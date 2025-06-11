<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeMessage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SendEmaliInvokableController extends Controller
{
    /**
     * Send a sample email to all user's emails.
     */
    public function __invoke(Request $request): JsonResponse
    {
        $user = $request->user();

        foreach ($user->emails as $email) {
            Mail::to($email->email)->send(new WelcomeMessage($user->name));
        }

        return response()->json(['message' => 'Emails sent successfully'], 200);
    }
}
