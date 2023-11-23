<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Exception\FirebaseException;
use Kreait\Firebase\Exception\MessagingException;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\Laravel\Facades\Telegram;
//use Telegram\Bot\Answers\Answerable;
use Telegram\Bot\Answers\Answerable;


class BotController extends Controller
{

    protected Api $telegram;

    /**
     * Create a new controller instance.
     *
     * @param Request $request
     * @throws TelegramSDKException
     */
    public function __construct(Request $request)
    {
//        $this->telegram = $telegram;
//        $response = Telegram::bot('user_1')->setWebhook(['url' => 'https://sms.ninjavps.tk/callback/user_1']);
//        $response = Telegram::bot('test_bot')->setWebhook(['url' => 'https://sms.ninjavps.tk/callback/test_bot']);

//        Log::info(json_encode($response));

//        $user_id = $request->all()['message']['from']['id'];
//        $user = User::where('telegram_user_id',$user_id)->first();
//        Auth::loginUsingId($user->id);
//        $token = $user->bot_token;


//        if(!empty($token))
//            $this->telegram->setAccessToken($token);

//        Log::info(json_encode($response));
    }



    /**
     * Show the bot information.
     */
    public function show()
    {
        $response = $this->telegram->getMe();

        return $response;
    }

    /**
     * @throws TelegramSDKException
     */
    public function callback(Request $request, $bot_name)
    {

        $chat_id = $request->all()['message']['chat']['id'];
        $text = $request->all()['message']['text'];
        $user = User::where('chat_id',$chat_id)->first();

        if(!isset($bot_name)) {
            Log::info(
                json_encode([
                    "user" => "bot_name not filled " . $chat_id,
                    "request" => $request->all(),
                    "bot_name" => $bot_name
                ])
            );
            return 1;
        }
        $update = Telegram::bot($bot_name)->commandsHandler(true);
//        $commands = Telegram::bot($bot_name)->getCommandBus()->getCommands();

        Log::info(
            json_encode([
                "user" => "not found for " . $chat_id,
                "request" => $request->all(),
                "bot_name" => $bot_name
            ])
        );


        $factory = (new Factory);
        $messaging = $factory->createMessaging();
        $message = CloudMessage::withTarget('topic','user_1')
//            ->withNotification(Notification::create('There is a new message', $text))
            ->withData(['key' => 'value']);

        try {
            $messaging->send($message);
        } catch (MessagingException $e1) {
            Log::info(json_encode([
                "error MessagingException" => $e1
            ]));
        } catch (FirebaseException $e2) {
            Log::info(json_encode([
                "error FirebaseException" => $e2
            ]));
        }


        if(empty($user)){

            Log::info(
                json_encode([
                    "user" => "not found for " . $chat_id,
                    "request" => $request->all(),
                    "bot_name" => $bot_name
                ])
            );

            $response = Telegram::bot($bot_name)->sendMessage([
                'chat_id' => $chat_id,
                'text' => "You haven't an active subscription " . $text . " " . $bot_name,
            ]);

            return response([], 400);

        }

        Auth::loginUsingId($user->id);


        $t = Telegram::bot($bot_name);
//
        $keyboard = [
            ['Send Message'],
//            ['4', '5', '6'],
//            ['1', '2', '3'],
//            ['0']
        ];
//
        $reply_markup = Keyboard::make([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);
//
//
        $response = Telegram::bot($bot_name)->sendMessage([
            'chat_id' => Auth::user()->chat_id,
            'text' => $t->getAccessToken() . " - ". $text . " " . $bot_name,
            'reply_markup' => $reply_markup
        ]);

        Log::info(
            json_encode([
                    "status" => "received",
                    "request" => $request->all(),
//                    "commands" => $commands
                ])
        );

        var_dump($request->all());
        die;
//        return null;
    }

    /**
     * @throws TelegramSDKException
     */
    public function forwardMessageToTelegram(Request $request): \Telegram\Bot\Objects\Message
    {
        $msg = $request->all()['message'];
        $from = $request->all()['from'];

        $response = Telegram::bot('user_' . $request->user()->id)->sendMessage([
            'chat_id' => Auth::user()->chat_id,
            'text' => $from . '
' . $msg
        ]);

       Telegram::bot('user_' . $request->user()->id)->setWebhook([
            'url' => env('TELEGRAM_WEBHOOK_URL', 'https://sms.tikoagency.ir/callback') . '/user_' . $request->user()->id,
        ]);

        return $response;
    }

    /**
     * @throws TelegramSDKException
     */
    public function play(Request $request) {
        $response = $this->telegram->getMe();

        $response = $this->telegram->sendMessage([
            'chat_id' => '118059084',
            'text' => 'Hello World'
        ]);

        $messageId = $response->getMessageId();

        var_dump($messageId);
        die();
    }

}
