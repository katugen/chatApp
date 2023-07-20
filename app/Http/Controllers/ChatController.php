<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Library\Message;   // for new Message;
use App\Events\MessageSent; // for MessageSent::dispatch()

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    // メッセージ送信時の処理
    public function sendMessage(Request $request)
    {
        // リクエストからデータの取り出し
        $strNickname = $request->input('nickname');
        $strMessage = $request->input('message');

        // メッセージオブジェクトの作成
        $message = new Message;
        $message->nickname = $strNickname;
        $message->body = $strMessage;

        // 送信者を含めてメッセージを送信
        //event( new MessageSent( $message ) ); // Laravel V7までの書き方
        MessageSent::dispatch($message);    // Laravel V8以降の書き方

        // 送信者を除いて他者にメッセージを送信
        // Note : toOthersメソッドを呼び出すには、
        //        イベントでIlluminate\Broadcasting\InteractsWithSocketsトレイトをuseする必要がある。
        //broadcast( new MessageSent($message))->toOthers();

        //return ['message' => $strMessage];
        return $request;
    }
}
