<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use LaravelFCM\Facades\FCM;
use LaravelFCM\Message\PayloadNotificationBuilder;
use LaravelFCM\Message\Topics;
use LaravelFCM\Message\Options;

class NotificationsController extends Controller
{
    // public function sendNotification(Request $request)
    // {
    //     $request->validate([
    //         'title' => 'required',
    //         'body' => 'required',
    //         'topic' => 'required',
    //     ]);

    //     $notificationBuilder = new PayloadNotificationBuilder('my title');
    //     $notificationBuilder->setBody('Hello world')
    //         ->setSound('default');

    //     $notification = $notificationBuilder->build();

    //     $topic = new Topics();
    //     $topic->topic('news');

    //     $topicResponse = FCM::sendToTopic($topic, null, $notification, null);

    //     $topicResponse->isSuccess();
    //     $topicResponse->shouldRetry();
    //     $topicResponse->error();
    // }

    public function sendNotification(Request $request)
    {

        $user = User::where('id', $request->id)->first();

        // $notification_id = $user->device_token;
        $notification_id = "e30cb809-676d-452a-84c0-44f70754593e";

        $title = "Greeting Notification";
        $message = "Have good day!";
        $id = 1;
        $type = "basic";

        $res = send_notification_FCM($notification_id, $title, $message, $id, $type);

        if ($res == 1) {

            return "usama";
        } else {

            return "failed";
        }
    }
}
