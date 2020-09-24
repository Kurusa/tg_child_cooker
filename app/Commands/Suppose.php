<?php

namespace App\Commands;

use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Suppose extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::SUPPOSE_RECIPE) {
            \App\Models\Suppose::create([
                'user_id' => $this->user->id,
                'text' => $this->update->getMessage()->getText()
            ]);
            $this->triggerCommand(SupposeImage::class);
        } else {
            $this->user->status = UserStatusService::SUPPOSE_RECIPE;
            $this->user->save();

            $buttons = [
                [$this->text['cancel']]
            ];
            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['write_recipe'], new ReplyKeyboardMarkup($buttons, false, true));
        }
    }

}