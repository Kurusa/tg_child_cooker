<?php

namespace App\Commands\AddRecipe;

use App\Commands\BaseCommand;
use App\Commands\MainMenu;
use App\Models\Recipe;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class CreatedByUser extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::RECIPE_CREATED_BY) {
            if (strpos($this->update->getMessage()->getText(), 't.me/') !== false) {
                Recipe::where('created_by_admin', $this->user->id)->where('status', 'NEW')->update([
                    'created_by_user' => $this->update->getMessage()->getText(),
                    'status' => 'DONE'
                ]);
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['recipe_done']);
                $this->triggerCommand(MainMenu::class);
            } elseif ($this->update->getMessage()->getText() == $this->text['skip']) {
                Recipe::where('created_by_admin', $this->user->id)->where('status', 'NEW')->update([
                    'status' => 'DONE'
                ]);
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['recipe_done']);
                $this->triggerCommand(MainMenu::class);
            }
        } else {
            $this->user->status = UserStatusService::RECIPE_CREATED_BY;
            $this->user->save();

            $buttons = [
                [$this->text['skip']], [$this->text['cancel']]
            ];
            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['created_by'], new ReplyKeyboardMarkup($buttons, false, true));
        }
    }

}