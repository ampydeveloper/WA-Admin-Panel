<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ChatEvent;
use App\Message;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::with(['user'])->get();

           return response()->json([
                'status' => true,
                'message' => 'messages successful',
                'data' => $messages
            ], 200);
    }

    public function store(Request $request)
    {

        $message = $request->user()->messages()->create([
            'body' => $request->message,
	    'receiver_id' => $request->receiver_id
        ]);

        broadcast(new ChatEvent($message->load('user')))->toOthers();
	$mesaage[] = $message;
        return response()->json($mesaage);
    }
}
