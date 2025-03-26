<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Conversation;
use Tymon\JWTAuth\Facades\JWTAuth;

class ConversationController extends Controller
{
    public function store(Request $request)
    {
        $auth = $this->jwtAuth();

        if ($auth['status'] !== 'success') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $validated = $request->validate([
            'title' => 'required',
        ]);

        $conversation = Conversation::create([
            'user_id' => JWTAuth::user()->id,
            'title' => $validated['title'],
        ]);

        return response()->json([
            'conversation' => $conversation,
        ]);
    }

    public function getConversations()
    {
        $auth = $this->jwtAuth();

        if ($auth['status'] !== 'success') {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $conversations = Conversation::where('user_id', JWTAuth::user()->id)->with('messages')->get();

        return response()->json([
            'conversations' => $conversations,
        ]);
    }
}
