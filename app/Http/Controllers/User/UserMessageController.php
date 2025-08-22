<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserMessage;
use App\Events\UserMessageReceived;

class UserMessageController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string|max:1000',
            'type' => 'nullable|string',
        ]);

        $message = UserMessage::create([
            'user_id' => $request->user_id,
            'message' => $request->message,
            'type' => $request->type ?? 'info',
        ]);

        broadcast(new UserMessageReceived($message))->toOthers();

        return response()->json(['success' => true, 'message' => 'Message sent successfully.']);
    }
}
