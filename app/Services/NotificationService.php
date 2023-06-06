<?php

namespace App\Services;

use App\Models\PushNotification;
use Illuminate\Http\Request;

class NotificationService
{
    public function sendNotification(Request $req)
    {
        // $pushNotification = new PushNotification();
        // $pushNotification->title = $req->input('title');
        // $pushNotification->body = $req->input('body');
        // // $pushNotification->img = $req->input('img');
        // $pushNotification->save();

        $url = 'https://fcm.googleapis.com/fcm/send';
        $dataArr = array('click_action' => 'FLUTTER_NOTIFICATION_CLICK', 'id' => $req->id, 'status' => "done");
        $notification = array('title' => $req->title, 'body' => $req->body, 'sound' => 'default', 'badge' => '2',);
        $arrayToSend = array('to' => "/topics/all", 'notification' => $notification, 'data' => $dataArr, 'priority' => 'high');
        $fields = json_encode($arrayToSend);
        $headers = array(
            'Authorization: key=' . "AAAAsAUkSUM:APA91bFe8zmoeIFSklLdb_C0RamxKiVsjG_qDHRIaPN_EWonUdEb1JrDqv7u251vm8WyEQSLUypocFSACsSWF2bTwn81LTUBaBY8ED0soj6t9JWo2wlWoLP8l73DPBTHMalFLktKi9Pj",
            'Content-Type: application/json'
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        $result = curl_exec($ch);
        //var_dump($result);
        curl_close($ch);
        // dd($result);
        return $result;
        // return redirect()->back()->with('success', 'Notification Sent successfully.');
    }
}
