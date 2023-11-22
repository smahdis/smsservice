<?php

namespace App\Http\Controllers\Commands;

use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Laravel\Facades\Telegram;

/**
 * Class HelpCommand.
 */
final class TestCommand extends Command
{
    /**
     * @var string Command Name
     */
    protected string $name = 'test';

    /**
     * @var array Command Aliases
     */
    protected array $aliases = ['testcommands'];

    /**
     * @var string Command Description
     */
    protected string $description = 'This is for test';

    /**
     * {@inheritdoc}
     * @throws TelegramSDKException
     */
    public function handle(): void
    {
        $commands = $this->telegram->getCommandBus()->getCommands();

        $text = '123sss';
        foreach ($commands as $name => $handler) {
            $text .= sprintf('/%s - %s'.PHP_EOL, $name, $handler->getDescription());
        }

//        $t = $this->makeDynamicBot([
//            "token" => '6928052247:AAFc5Iwmk5pNvQ2nQP5mhsy88GIr7F9OJHE',
//            "async_requests" => false,
//            "http_client_handler" => null,
//            "resolve_command_dependencies" => null,
//            'command' => [
//                'start' => StartCommand::class
//            ]
//        ]);

        $this->replyWithMessage(['text' => $text]);
    }

//
//    /**
//     * Make a dynamic bot instance.
//     *
//     * @param string $name
//     *
//     * @return Api
//     * @throws TelegramSDKException
//     */
//    protected function makeDynamicBot($params): Api
//    {
//        $telegram = new Api(
//            $params['token'],
//            $params['async_requests'],
//            $params['http_client_handler']
//        );
//
//
//        // Check if DI needs to be enabled for Commands
//        if ($params['resolve_command_dependencies'] && isset($this->container)) {
//            $telegram->setContainer($this->container);
//        }
//
//        $commands = $params['command'];
//        $commands = Telegram::parseBotCommands($commands);
//
//        // Register Commands
//        $telegram->addCommands($commands);
//
//        Log::info(
//            json_encode([
//                "status" => "test_command123",
//                "telegram" => json_encode($telegram->getCommandBus()->getCommands()),
//                "me" => Telegram::getBots()
//            ])
//        );
//
//        $telegram->setWebhook(['url' => env('TELEGRAM_WEBHOOK_URL', 'https://bd1b-5-125-138-173.ngrok-free.app/callback')]);
//        return $telegram;
//    }
}
