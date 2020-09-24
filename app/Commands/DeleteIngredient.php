<?php

namespace App\Commands;

use App\Models\Ingredient;
use Illuminate\Database\Capsule\Manager as DB;

class DeleteIngredient extends BaseCommand
{

    function processCommand($text = false)
    {
        $admin_list = explode(',', env('ADMIN_LIST'));
        if (in_array($this->update->getMessage()->getFrom()->getId(), $admin_list)) {
            $ingredient = explode('/delete_ingredient ', $this->update->getMessage()->getText())[1];

            $possible_ingredient = Ingredient::where('title', $ingredient)->first();
            if (!$possible_ingredient) {
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['ingredient_not_found'], 'html', false, $this->update->getMessage()->getMessageId());
            } else {
                DB::statement('DELETE FROM ingredient WHERE title = "' . $ingredient . '"');
                $this->getBot()->sendMessage($this->user->chat_id, $this->text['ingredient_deleted'], 'html', false, $this->update->getMessage()->getMessageId());
            }
        }
    }

}