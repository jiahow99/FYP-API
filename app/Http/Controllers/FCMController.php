<?php

namespace App\Http\Controllers;

use App\Http\Requests\AcceptMechanicRequest;
use App\Http\Requests\RequestMechanicRequest;
use App\Http\Requests\RequestTyreChange;
use App\Models\Mechanic;
use App\Models\Part;
use App\Models\Store;
use Illuminate\Http\Request;

class FCMController extends Controller
{
    public function sendRequest(Request $request) {
        // Store token
        $store = Store::where('place_id', $request->placeId)->first();
        // Get username
        $userName = $request->name;

        $url = 'https://fcm.googleapis.com/fcm/send';            
        $serverKey = env('FCM_SERVER_KEY'); 
        $data = [
            "to" => $store->token,
            "notification" => [
                "title" => "Mechanic Request",
                "body" => "$userName request mechanic from you.",
            ],
            "data" => $request->all(),
        ];
        $encodedData = json_encode($data);
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
    }


    public function acceptRequest(Request $request) 
    {
        // Mechanic info
        $message = $request->all();
        if ($request->status == 'accepted') {
            $mechanic = Store::where('place_id', $request->placeId)->first()->mechanic;
            $message['mechanic'] = $mechanic;
        }

        $url = 'https://fcm.googleapis.com/fcm/send';            
        $serverKey = env('FCM_SERVER_KEY'); 
        $data = [
            "to" => $request->userToken,
            "notification" => [
                "title" => "Accepted Request",
                "body" => "{$request->storeName} accepted your request.",
            ],
            "data" => $message,
        ];
        $encodedData = json_encode($data);
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
    }

    public function requestTyreChange(Request $request) 
    {
        // Store info
        $store = Store::where('place_id', $request->placeId)->first();
        // Tyre image
        $part = Part::find($request->tyreId)->load('images');
        $message = $request->all();
        $message['image'] = $part->images[0]->url;

        $url = 'https://fcm.googleapis.com/fcm/send';            
        $serverKey = env('FCM_SERVER_KEY'); 
        $data = [
            "to" => $store->token,
            "notification" => [
                "title" => "Tyre Change Request",
                "body" => "$request->name request mechanic from you.",
            ],
            "data" => $message,
        ];
        $encodedData = json_encode($data);
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
    }

    public function completeRequest(Request $request) 
    {
        $message = $request->all();
        $message['status'] = 'completed';

        $url = 'https://fcm.googleapis.com/fcm/send';            
        $serverKey = env('FCM_SERVER_KEY'); 
        $data = [
            "to" => $request->userToken,
            "notification" => [
                "title" => "Mechanic has arrived.",
                "body" => "Service has been completed.",
            ],
            "data" => $message,
        ];
        $encodedData = json_encode($data);
        $headers = [
            'Authorization:key=' . $serverKey,
            'Content-Type: application/json',
        ];
    
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encodedData);
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }        
        // Close connection
        curl_close($ch);
    }
}
