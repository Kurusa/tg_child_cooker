<?php

namespace App\Commands;

use App\Models\User;

class RecipeModerate extends BaseCommand
{

    function processCommand()
    {
        $callback_data = \json_decode($this->update->getCallbackQuery()->getData(), true);
        $suppose = \App\Models\Suppose::where('id', $callback_data['id'])->first();
        $user = User::where('id', $suppose->user_id)->get();

        if ($callback_data['a'] == 'recipe_decline') {
            $suppose->moderated = 0;
            $this->getBot()->sendMessage($user[0]->chat_id, $this->text['sorry_you_declined']);
        } else {
            $suppose->moderated = 1;
            $this->getBot()->sendMessage($user[0]->chat_id, $this->text['your_recipe_accepted']);
            $this->getBot()->sendMessage($this->user->chat_id, $this->text['now_create_recipe']);
        }

        $suppose->save();
    }

}