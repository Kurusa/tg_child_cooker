<?php

namespace App\Utils;

class TelegramKeyboard
{

    static $columns = 1;
    static $list;

    static $user_id;
    static $buttons = [];

    static function build()
    {
        if (self::$list) {
            $one_row = [];

            foreach (self::$list as $listKey) {
                $checked_ingredient = \App\Models\SearchRecipe::where('user_id', self::$user_id)->where('ingredient_id', $listKey->id)->get();
                $text = $listKey->title;
                $text .= $checked_ingredient->count() ? ' ☑️' : '';

                $one_row[] = [
                    'text' => $text,
                    'callback_data' => json_encode([
                        'a' => 'search_ing',
                        'id' => $listKey->id,
                        'checked' => $checked_ingredient->count() ? true : false,
                    ]),
                ];

                if (count($one_row) == self::$columns) {
                    self::$buttons[] = $one_row;
                    $one_row = [];
                }
            }

            if (count($one_row) > 0) {
                self::$buttons[] = $one_row;
            }
        }
    }

    static function addButton($text, $callback)
    {
        self::$buttons[] = [[
            'text' => $text,
            'callback_data' => json_encode($callback),
        ]];
    }

    static function addButtonUrl($text, $url)
    {
        self::$buttons[] = [[
            'text' => $text,
            'url' => $url
        ]];
    }

    static function get()
    {
        return self::$buttons;
    }

}