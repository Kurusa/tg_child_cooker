<?php

namespace App\Commands;

use App\Models\Ingredient;

class AddIngredient extends BaseCommand
{

    function processCommand($text = false)
    {
        $admin_list = explode(',', env('ADMIN_LIST'));
        if (in_array($this->update->getMessage()->getFrom()->getId(), $admin_list)) {
            $ingredient = explode('/add_ingredient ', $this->update->getMessage()->getText())[1];

            $possible_ingredient = Ingredient::where('title', $ingredient)->first();
            if (!$possible_ingredient) {
                Ingredient::create([
                    'title' => $ingredient
                ]);
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['ingredient_done'], 'html', false, $this->update->getMessage()->getMessageId());
            } else {
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['ingredient_already_exist'], 'html', false, $this->update->getMessage()->getMessageId());
            }
        }
    }

}