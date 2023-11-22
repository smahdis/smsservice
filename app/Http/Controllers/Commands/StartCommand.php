<?php

namespace App\Http\Controllers\Commands;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Exceptions\TelegramSDKException;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'Start Command to get you started';

    /**
     * @throws TelegramSDKException
     */
    public function handle(): void
    {
        $fallbackUsername = $this->getUpdate()->getMessage();

//        Log::info(
//            json_encode([
//                "status" => "start_command",
//                "$fallbackUsername" =>$fallbackUsername,
//                "user" => Auth::user()
//            ])
//        );



        $this->replyWithMessage([
            'text' => $this->getUpdate()->getChat()['id'],
        ]);
    }


}
