<?php

namespace App\Http\Controllers;

use LRedis;
use App\Job;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\User;

class ChatController extends Controller {

    public function __construct() {
        
    }

    public function jobChat(Request $request) {
        $data = array(
            'jobId' => $request->jobId,
        );
        $postData = json_encode($data);
        $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, "https://wa.customer.leagueofclicks.com/:" . env('SOCKET_SERVER_PORT') . "/job-chat");
        curl_setopt($ch, CURLOPT_URL, "https://wa.customer.leagueofclicks.com:3100/job-chat");
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
        if (!empty($messages)) {
            $messages = array_reverse($messages);
        } else {
            $messages = [];
        }
        foreach($messages as $key=>$message){
            $messages[$key]->job_id = (int)$message->job_id;
            if(isset($message->username)){
            $messages[$key]->username = (int)$message->username;
            }
        }
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
                    }])->with(['manager' => function($q) {

                        $q->select('id', 'first_name', 'user_image');
                    }])->with(['truck_driver' => function($q) {

                        $q->select('id', 'first_name', 'user_image');
                    }])->with(['skidsteer_driver' => function($q) {

                        $q->select('id', 'first_name', 'user_image');
                    }])->first();

        if(isset($chatMembers->customer->user_image)){
        $chatMembers->customer->user_image = env('CUSTOMER_URL') . '/storage/user_images/' . $chatMembers->customer->id . '/' . $chatMembers->customer->user_image;
        }
        if(isset($chatMembers->manager->user_image)){
        $chatMembers->manager->user_image = env('CUSTOMER_URL') . '/storage/user_images/' . $chatMembers->manager->id . '/' . $chatMembers->manager->user_image;
        }
        if(isset($chatMembers->skidsteer_driver->user_image)){
        $chatMembers->skidsteer_driver->user_image = env('APP_URL') . '/' . $chatMembers->skidsteer_driver->user_image;
        }
        if(isset($chatMembers->truck_driver->user_image)){
        $chatMembers->truck_driver->user_image = env('APP_URL') . '/' . $chatMembers->truck_driver->user_image;
        }

        $all_admin = User::where('role_id', config('constant.roles.Admin'))->select('id', 'first_name', 'user_image')->get();
        foreach ($all_admin as $key => $admin) {
            $all_admin[$key]->user_image = env('APP_URL') . '/' . $admin->user_image;
        }
        $chatMembers2 = collect($chatMembers);
        $all_admin2 = collect(array('admin' => $all_admin));
        $allChatMembers = $chatMembers2->merge($all_admin2);

         $all_manager = User::where('role_id', config('constant.roles.Admin_Manager'))->select('id', 'first_name', 'user_image')->get();
        foreach ($all_manager as $key => $manager) {
            $all_manager[$key]->user_image = env('APP_URL') . '/' . $manager->user_image;
        }
        $allChatMembers2 = collect($allChatMembers);
        $all_manager2 = collect(array('admin_manager' => $all_manager));
        $allChatMembersTotal = $allChatMembers2->merge($all_manager2);

        
        return response()->json([
                    'status' => true,
                    'message' => 'Chat members',
                    'data' => $allChatMembersTotal
                        ], 200);
    }

}
