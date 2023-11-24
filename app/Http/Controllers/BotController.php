<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
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
    public function handleReplyMessage($bot_name, $chat_id, $user=null): void
    {
        Log::info(
            json_encode([
                "step" => session('step'),
                "state" => session('state'),
                "param" => session('params'),
                "text" => session('text'),
                "user" => $user
//                "callback id" =>  $update->callbackQuery->id
            ])
        );

//        $reply_markup = Telegram::replyKeyboardHide();

//        $reply_markup = Keyboard::make([
//            'keyboard' => $keyboard,
//            'resize_keyboard' => true,
//            'one_time_keyboard' => true
//        ]);


        // Normal message:



//        Telegram::bot($bot_name)->answerCallbackQuery([
//            'callback_query_id' => $update->callbackQuery->id,
//            'text' => 'How can I help you?',
////            'show_alert' => true,
//        ]);


        $step = session("step");
        switch ($step) {
            case 1:
                session(['step' => 2]);
                $keyboard = [['لغو']];
                $reply_markup = Keyboard::make([
                    'keyboard' => $keyboard,
                    'resize_keyboard' => true,
                    'one_time_keyboard' => true
                ]);
                Telegram::bot($bot_name)->sendMessage([
                    'chat_id' => $chat_id,
                    'text'    => 'لطفا پیام خود را بنویسید',
                    'reply_markup' => $reply_markup
                ]);
                break;
            case 2:
                session(['step' => 3]);
//                session(['state' => 'reply', "step" => 3, "params" => $params]);
                $params = session('params');
                $reply_markup = Keyboard::make()
                    ->inline()
                    ->row([
                        Keyboard::inlineButton(['text' => 'ارسال', 'callback_data' => json_encode([
                            "type" => "reply_send",
//                    "step" => "1",
                            "from" => $params['from'],
                            "text" => $params['text'],
                        ])])
                    ]);
                $response = Telegram::bot($bot_name)->sendMessage([
                    'chat_id' => $chat_id,
                    'text' => $params['from'] . '
' . $params['text'],
                    'reply_markup' => $reply_markup
                ]);

                Log::info(json_encode([
                    "ersal messsage respomse" => $response
                ]));


                session('message_id', $response->getMessageId());
                break;

            case 3:

                session(['step' => 0]);
                session(['state' => ""]);
                $params = session('params');

                $factory = (new Factory);
                $messaging = $factory->createMessaging();
                $message = CloudMessage::withTarget('topic','user_1')
//            ->withNotification(Notification::create('There is a new message', $text))
                    ->withData([
                        "sms_to" => $params['from'],
                        "sms_text" => $params['text'],
                    ]);

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

                $msg_id = session('message_id');

                Log::info(json_encode([
                    "msg_id" => $msg_id
                ]));

                Telegram::bot($bot_name)->editMessageText([
                    'chat_id'   => $chat_id,
                    'message_id'    =>  $msg_id,
                    'text'  =>  "پیام ارسال شد.",
                ]);


//                Telegram::bot($bot_name)->sendMessage([
//                    'chat_id' => $chat_id,
//                    'text' => "message sent"
//                ]);

                break;

            default:

        }
    }

    /**
     * @throws TelegramSDKException
     */
    public function callback(Request $request, $bot_name)
    {

        $update = Telegram::bot($bot_name)->commandsHandler(true);

        if($update->isType('callback_query')) {

            $params = json_decode($update->callbackQuery->data, true);
            $type = $params['type'];
            if($type === "reply_start") {
                session(['step' => 1]);
                $request->session()->put('state', 'reply123');
                session(['state' => 'reply', "params" => $params]);
            }


            $this->handleReplyMessage($bot_name, $update->callbackQuery->message->chat->id);

            return 0;
        }

        $chat_id = $request->all()['message']['chat']['id'];
        $text = $request->all()['message']['text'];
        $user = User::where('chat_id',$chat_id)->first();

//        if(!empty($user))
//            Auth::loginUsingId($user->id);
//        Log::info(
//            json_encode([
//                "session id" => $request->session()->getId(),
//                "step" => session('step'),
//                "state" => $request->session()->get('state'),
//                "param" => session('params'),
////                "callback id" =>  $update->callbackQuery->id
//            ])
//        );

        if(session('state') == "reply") {
            $params = session('params');
            $params["text"] = $text;
            session(['params'=> $params]);
            $this->handleReplyMessage($bot_name, $chat_id, $user);
            return 0;
        }



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

//        $commands = Telegram::bot($bot_name)->getCommandBus()->getCommands();

        Log::info(
            json_encode([
                "user" => "not found for " . $chat_id,
                "request" => $request->all(),
                "bot_name" => $bot_name
            ])
        );



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

        $keyboard = [
            ['Reply'],
        ];
//
        $reply_markup = Keyboard::make()
            ->inline()
            ->row([
                Keyboard::inlineButton(['text' => 'پاسخ', 'callback_data' => json_encode([
                    "type" => "reply_start",
//                    "step" => "1",
                    "from" => $from,
                ])])
            ]);

        $response = Telegram::bot('user_' . $request->user()->id)->sendMessage([
            'chat_id' => Auth::user()->chat_id,
            'reply_markup' => $reply_markup,
            'text' => $from . '
' . $msg
        ]);

        Telegram::bot('user_' . $request->user()->id)->setWebhook([
            'url' => env('TELEGRAM_WEBHOOK_URL', 'https://sms.tikoagency.ir/callback') . '/user_' . $request->user()->id,
        ]);

        return $response;
    }


}
