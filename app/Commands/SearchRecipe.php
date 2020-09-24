<?php

namespace App\Commands;

use App\Utils\TelegramKeyboard;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Illuminate\Database\Capsule\Manager as DB;

class SearchRecipe extends BaseCommand
{

    function processCommand()
    {
        if ($this->update->getCallbackQuery()) {
            $callback_data = \json_decode($this->update->getCallbackQuery()->getData(), true);
            if ($callback_data['a'] !== 'next_ingredient' && $callback_data['a'] !== 'prev_ingredient' && $callback_data['a'] !== 'next_ingredient_end' && $callback_data['a'] !== 'prev_ingredient_end') {
                if ($callback_data['checked'] == false) {
                    \App\Models\SearchRecipe::create([
                        'user_id' => $this->user->id,
                        'ingredient_id' => $callback_data['id']
                    ]);
                } else {
                    DB::statement('DELETE FROM search_recipe WHERE user_id = ' . $this->user->id . ' AND ingredient_id = ' . $callback_data['id']);
                }
            }
            $offset = 0;
            if ($callback_data['a'] == 'next_ingredient') {
                $offset = 80;
            } elseif ($callback_data['a'] == 'next_ingredient_end') {
                $offset = 160;
            }
            if ($callback_data['a'] == 'prev_ingredient_end') {
                $offset = 80;
            }

            $ingredient_list = \App\Models\Ingredient::skip($offset)->take(80)->orderBy('title')->get();
            TelegramKeyboard::$columns = 2;
            TelegramKeyboard::$user_id = $this->user->id;
            TelegramKeyboard::$list = $ingredient_list;
            TelegramKeyboard::build();

            if ($callback_data['a'] == 'next_ingredient' || $callback_data['a'] == 'prev_ingredient_end') {
                TelegramKeyboard::addButton('Назад', [
                    'a' => 'prev_ingredient'
                ]);
            }

            if ($callback_data['a'] == 'next_ingredient_end') {
                TelegramKeyboard::addButton('Назад', [
                    'a' => 'prev_ingredient_end'
                ]);
            }

            if ($callback_data['a'] !== 'next_ingredient_end') {
                TelegramKeyboard::addButton('Дальше', [
                    'a' => 'next_ingredient_end'
                ]);
            }

            $current_search_recipe = \App\Models\SearchRecipe::where('user_id', $this->user->id)->first();
            if ($current_search_recipe) {
                TelegramKeyboard::addButton($this->text['done'], [
                    'a' => 'search_ing_done',
                ]);
            }

            $this->getBot()->editMessageReplyMarkup($this->user->chat_id, $this->update->getCallbackQuery()->getMessage()->getMessageId(), new InlineKeyboardMarkup(TelegramKeyboard::get()));
        } else {
            $ingredient_list = \App\Models\Ingredient::take(80)->orderBy('title')->get();
            TelegramKeyboard::$columns = 2;
            TelegramKeyboard::$user_id = $this->user->id;
            TelegramKeyboard::$list = $ingredient_list;
            TelegramKeyboard::build();
            TelegramKeyboard::addButton('Дальше', [
                'a' => 'next_ingredient'
            ]);
            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['select_recipe_ingredients'], new InlineKeyboardMarkup(TelegramKeyboard::get()));
        }
    }

}