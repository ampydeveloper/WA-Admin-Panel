<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Events\ChatEvent;
use App\Message;

class MessageController extends Controller
{
   public function send(Request $request)
	{
		$data = [
			'message' => $request->input('message'),
			'nickname' => $request->input('nickname'),
		];
		event(new ChatEvent($data));
                return response()->json([
                'status' => 'message is sent successfuly!'
               ]);
	}
}
