<?php

namespace App\Commands\AddRecipe;

use App\Commands\BaseCommand;
use App\Commands\MainMenu;
use App\Models\Recipe;
use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class Title extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::RECIPE_TITLE) {
            if ($this->update->getMessage()->getText() == $this->text['cancel']) {
                $this->triggerCommand(MainMenu::class);
                exit();
            }
            Recipe::create([
                'title' => $this->update->getMessage()->getText(),
                'created_by_admin' => $this->user->id
            ]);
            $this->triggerCommand(Ingredient::class);
        } else {
            $this->user->status = UserStatusService::RECIPE_TITLE;
            $this->user->save();

            $buttons = [
                [$this->text['cancel']]
            ];
            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['write_recipe_title'], new ReplyKeyboardMarkup($buttons, false, true));
        }
    }

}