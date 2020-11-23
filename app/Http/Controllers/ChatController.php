<?php

namespace App\Http\Controllers;

use LRedis;
use App\Job;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller {

    public function __construct() {
        
    }

    public function jobChat() {
//        if (Auth::check()) {
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, "http://" . env('SOCKET_SERVER_IP') . ":" . env('SOCKET_SERVER_PORT'));
//            // SSL important
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//            // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//
//            $output = curl_exec($ch);
//            curl_close($ch);
//
//            // dd($output);
//            $messages = json_decode($output);
//            $messages = array_reverse($messages);
//            return view('chat.public', ['messages' => $messages]);
//        }
//
//        session()->flush();
//        return redirect(route('frontend.enterLiveStreaming'));
        $data = array(
            'jobId' => 46,
        );
        $postData = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://" . env('SOCKET_SERVER_IP') . ":" . env('SOCKET_SERVER_PORT') . "/job-chat");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $output = curl_exec($ch);
        curl_close($ch);
//        print_r($output);
//        die('re');
        $messages = json_decode($output);
        $messages = array_reverse($messages);
        
            return response()->json([
                    'status' => true,
                    'message' => 'Chat messages',
                    'data' => $messages
                        ], 200);
            
    }

    public function privateChat() {
        $token = "fdbfdbfd";
        $username = auth()->user()->username;

        $userId = auth()->user()->id;
        $modelId = 1; // this is we get by match token in db with models  // for testing i user admin account
        $getUniqueID = $this->getUniqueID($userId, $modelId);

        //
        $data = array(
            'uniqueid' => $getUniqueID,
        );
        $postData = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://" . env('SOCKET_SERVER_IP') . ":" . env('SOCKET_SERVER_PORT') . "/private-chat");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        // curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output = curl_exec($ch);
        //
        $messages = json_decode($output);
        $messages = array_reverse($messages);

        return view('frontend.chat.private', ['token' => $token, 'username' => $username, 'getUniqueID' => $getUniqueID, 'messages' => $messages]);
    }

    public function modelChat() {
        $token = "fdbfdbfd";
        $username = "modelname";

        $modelId = auth()->user()->id;
        $userId = 121; // this is we get by match token in db with models
        $getUniqueID = $this->getUniqueID($userId, $modelId);

        //
        $data = array(
            'uniqueid' => $getUniqueID,
        );
        $postData = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "http://" . env('SOCKET_SERVER_IP') . ":" . env('SOCKET_SERVER_PORT') . "/private-chat");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        // curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        $output = curl_exec($ch);
        //
        $messages = json_decode($output);
        $messages = array_reverse($messages);

        return view('frontend.chat.private', ['token' => $token, 'username' => $username, 'getUniqueID' => $getUniqueID, 'messages' => $messages]);
    }

    private function getUniqueID($a, $b) {
        if ($a < $b)
            return $a . "-" . $b;
        else
            return $b . "-" . $a;
    }
    
    public function chatMembers(Request $request) {
        $chatMembers = Job::whereId($request->job_id)->select('id', 'customer_id', 'manager_id', 'truck_driver_id', 'skidsteer_driver_id')->with(['customer' => function($q) {
            $q->select('id', 'first_name', 'user_image');
        }]) ->with(['manager' => function($q) {
        
            $q->select('id', 'first_name', 'user_image');
        }])->with(['truck_driver' => function($q) {
        
            $q->select('id', 'first_name', 'user_image');
        }])->with(['skidsteer_driver' => function($q) {
        
            $q->select('id', 'first_name', 'user_image');
        }])->first();
        return response()->json([
                    'status' => true,
                    'message' => 'Chat members',
                    'data' => $chatMembers
                        ], 200);
    }

}
