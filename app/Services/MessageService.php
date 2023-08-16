<?php
namespace App\Services;

use Illuminate\Support\Facades\Session;

class MessageService
{
    public function setMessage($text, $type = 'success', $title = '', $time = 3) {
        $message = new \stdClass();
        $message->text = $text;
        $message->type = $type;
        $message->time = $time*1000;
        $message->title = $title;
        if (Session::get('messages')) {
            $messages = Session::get('messages');
        } else {
            $messages = [];
        }
        $messages[] = $message;
        Session::flash('messages', $messages);
    }

    public function getMessages() {
        $messages = Session::get('messages');
        Session::forget('messages');
        if ($messages) {
            return $messages;
        } else {
            return null;
        }
    }
}

