<?php

namespace App\Commands;

use App\Models\Ingredient;

class IngredientList extends BaseCommand
{

    function processCommand($text = false)
    {
        $admin_list = explode(',', env('ADMIN_LIST'));
        if (in_array($this->update->getMessage()->getFrom()->getId(), $admin_list)) {
            $ingredient_list = Ingredient::all();
            $message = '';
            foreach ($ingredient_list as $ingredient) {
                $message .= $ingredient->title . "\n";
            }
            $this->getBot()->sendMessage($this->user->chat_id, $message, 'html', false, $this->update->getMessage()->getMessageId());
        }
    }

}