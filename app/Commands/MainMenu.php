<?php

namespace App\Commands;

use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class MainMenu extends BaseCommand
{

    function processCommand($text = false)
    {
        $this->user->status = UserStatusService::DONE;
        $this->user->save();

        $buttons = [
            [$this->text['search_recipe']], [$this->text['suppose']]
        ];

        $admin_list = explode(',', env('ADMIN_LIST'));
        if (in_array($this->update->getMessage()->getFrom()->getId(), $admin_list)) {
            $buttons[] = [$this->text['add_recipe']];
        }

        $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['start'], new ReplyKeyboardMarkup($buttons, false, true));
    }

}