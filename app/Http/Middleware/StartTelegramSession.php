<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Update;

class StartTelegramSession extends StartSession
{
    public function handle($request, Closure $next)
    {
        $result = parent::handle($request, $next);
        session()->save();
        return $result;
    }

    /**
     * Get the session implementation from the manager.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Session\Session
     */
    public function getSession(Request $request): \Illuminate\Contracts\Session\Session
    {
        /** @var Api $telegram */
        $telegram = app('telegram');
        $update = $telegram->getWebhookUpdate();
        $sessionName = null;
        if ($update instanceof Update) {

            if ($update->getCallbackQuery()) {
                $sessionName = $update->getCallbackQuery()->getFrom()->getId();

                Log::info(
                    json_encode([
                        "update->getCallbackQuery()" => $update->getCallbackQuery(),
                        "sessionName" => $sessionName,
                    ])
                );

            } else if ($update->getMessage()) {

                $sessionName = $update->getMessage()->getFrom()->getId();
                Log::info(
                    json_encode([
                        "update" => $update,
                        "sessionName" => $sessionName,
                    ])
                );

            }
        }

        if ($sessionName) {
            return tap($this->manager->driver(), function ($session) use ($sessionName) {
                $session->setId(str_pad($sessionName, 40, "0", STR_PAD_LEFT));
            });
        } else {
            return $this->manager->driver();
        }
    }
}
