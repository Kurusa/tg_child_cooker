<?php

namespace App\Commands\AddRecipe;

use App\Commands\BaseCommand;
use App\Models\Recipe;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Link extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::RECIPE_LINK) {
            if (strpos($this->update->getMessage()->getText(), 'https') !== false) {
                Recipe::where('created_by_admin', $this->user->id)->where('status', 'NEW')->update([
                    'telegraph_link' => $this->update->getMessage()->getText()
                ]);
                $this->triggerCommand(CreatedByUser::class);
            } else {
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['wrong_recipe_link']);
            }
        } else {
            $this->getBot()->deleteMessage($this->user->chat_id, $this->update->getCallbackQuery()->getMessage()->getMessageId());
            $this->user->status = UserStatusService::RECIPE_LINK;
            $this->user->save();

            $buttons = [
                [$this->text['cancel']]
            ];
            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['write_recipe_link'], new ReplyKeyboardMarkup($buttons, false, true));
        }
    }

}