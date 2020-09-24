<?php

namespace App\Commands\AddRecipe;

use App\Commands\BaseCommand;
use App\Models\Recipe;
use App\Models\RecipeIngredient;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Illuminate\Database\Capsule\Manager as DB;

class Ingredient extends BaseCommand
{

    function processCommand()
    {
        if ($this->update->getCallbackQuery()) {
            $callback_data = \json_decode($this->update->getCallbackQuery()->getData(), true);
            $current_recipe = Recipe::where('created_by_admin', $this->user->id)->where('status', 'NEW')->first();
            if ($callback_data['checked'] == false) {
                RecipeIngredient::create([
                    'recipe_id' => $current_recipe->id,
                    'ingredient_id' => $callback_data['id']
                ]);
            } else {
                DB::statement('DELETE FROM recipe_ingredient WHERE recipe_id = ' . $current_recipe->id . ' AND ingredient_id = ' . $callback_data['id']);
            }

            $buttons = [];
            $ingredient_list = \App\Models\Ingredient::all();
            foreach ($ingredient_list as $ingredient) {
                $checked_ingredient = RecipeIngredient::where('recipe_id', $current_recipe->id)->where('ingredient_id', $ingredient->id)->get();
                $text = $ingredient->title;
                $text .= $checked_ingredient->count() ? ' ☑️' : '';
                $buttons[] = [[
                    'text' => $text,
                    'callback_data' => json_encode([
                        'a' => 'ing',
                        'id' => $ingredient->id,
                        'checked' => $checked_ingredient->count() ? true : false
                    ])
                ]];
            }

            $current_recipe_ingredients = RecipeIngredient::where('recipe_id', $current_recipe->id)->get(['ingredient_id']);
            if ($current_recipe_ingredients->count()) {
                $buttons[] = [[
                    'text' => $this->text['done'],
                    'callback_data' => json_encode([
                        'a' => 'ing_done',
                    ])
                ]];
            }

            $this->getBot()->editMessageReplyMarkup($this->user->chat_id, $this->update->getCallbackQuery()->getMessage()->getMessageId(), new InlineKeyboardMarkup($buttons));
        } else {
            $buttons = [];
            $ingredient_list = \App\Models\Ingredient::all();
            foreach ($ingredient_list as $ingredient) {
                error_log($ingredient->title);
                $buttons[] = [[
                    'text' => $ingredient->title,
                    'callback_data' => json_encode([
                        'a' => 'ing',
                        'id' => $ingredient->id,
                        'checked' => false
                    ])
                ]];
            }
            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['select_recipe_ingredients'], new InlineKeyboardMarkup($buttons));
        }
    }

}