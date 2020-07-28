<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\AppUser;
use App\UserNotificationToken;
use App\Message;
use Edujugon\PushNotification\Facades\PushNotification;


class UserApiController extends Controller
{
    public function login(LoginRequest $request, JWTAuth $JWTAuth)
    {
        $credentials = $request->only(['email', 'password']);

        try {
            $token = Auth::guard()->attempt($credentials);

            if(!$token) {
                throw new AccessDeniedHttpException();
            }

        } catch (JWTException $e) {
            throw new HttpException(500);
        }

        return response()
            ->json([
                'status' => 'ok',
                'token' => $token,
                'expires_in' => Auth::guard()->factory()->getTTL() * 60
            ]);
    }

    public function searchUser(Request $request){
        $user_id = $request->input('user_id');
        $search = $request->input('search');
        $results = [];
        if($request->has('users_only')){

            $results = AppUser::where('user_type','!=', 3)
                            ->where(function ($query) use($search){
                                $query->where('first_name', 'like', '%'.$search.'%')
                                        ->orWhere('last_name', 'like', '%'.$search.'%');
                            })
                            ->whereNotIn('id', [$user_id])
                            ->offset(0)->limit(50)
                            ->get();

            
        }
        return response()->json([
            'status' => $user_id,
            'response' => $results,
        ], 200);
    }

    public function sendMessage(Request $request){

        $payload = $request->input('payload');

        $message = new Message;
        $message->from_id = $payload['from_id'];
        $message->message = $payload['message'];
        $message->media = '';

        if (isset($payload['to_id'])) {
            $message->to_id = $payload['to_id'];

            $notification_title = 'New Message From '. $request->input('name');
            $notification_body = $message->message;
            $notification_user_id = $payload['to_id'];
            $notification_ios = [
                'aps' => [
                    'alert' => [
                        'title' => $notification_title,
                        'body' => $notification_body
                    ],
                    'sound' => 'default',
                    'badge' => 1

                ],
                'data' => ['action' => 'new_message', 'user_id'=> $notification_user_id]
            ];

            $notification_android = [
                    'data' => ['action' => 'new_message',
                                'user_id'=> $notification_user_id,
                                'title'=>$notification_title,
                                'message'=>$notification_body,
                            ],
                ];

            $response = $this->trackSendNotification('new_message', $notification_ios, $notification_android, $notification_user_id);
        }else{
            $message->to_id = "0";
            $message->group_id = $payload['group_id'];;
        }
        $message->save();
        return response()->json([
            'status' => true,
            'id' => $message->id,
            'message' => 'Message sent.'
        ], 200);
    }

    public function saveNotificationToken(Request $request){

        $token = UserNotificationToken::where('token', $request->input('token'))->first();
        if($token){

        }else{
            $token = new UserNotificationToken;
        }

        $token->user_id = $request->input('user_id');
        $token->token = $request->input('token');
        $token->device_type = $request->input('device_type');

        $token->save();

        return response()->json([
            'status' => true
        ], 200);
    }

    public function trackSendNotification($notification_type, $notification_ios, $notification_android, $user_id){
        $device_tokens = UserNotificationToken::where('user_id', $user_id)->get();
        foreach($device_tokens as $index => $token){
            $response = '';
            $notification = '';
            if($token->device_type == 'iOS'){
                $ios_token = [];
                array_push($ios_token, $token->token);
                $notification = $notification_ios;
                $response = $this->sendNotification('apn', $notification_ios, $ios_token);
            }else{
                $notification = $notification_android;
                $android_token = [];
                array_push($android_token, $token->token);
                $response = $this->sendNotification('fcm', $notification_android, $android_token);
            }
            return $response;
        }
    }
    public function sendNotification($service, $notification, $tokens){
        $push = PushNotification::setService($service)
            ->setMessage($notification)
            ->setDevicesToken($tokens)
            ->send();

        return $push->getFeedback();
    }
}
