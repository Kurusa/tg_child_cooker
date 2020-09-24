<?php

namespace App\Commands;

use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use Illuminate\Database\Capsule\Manager as DB;

class SearchRecipeDone extends BaseCommand
{

    function processCommand()
    {
        $search_ingredients = \App\Models\SearchRecipe::where('user_id', $this->user->id)->get();

        $where = [];
        foreach ($search_ingredients as $ingredient) {
            $where[] = $ingredient->ingredient_id;
        }

        $recipe_list = DB::select('select recipe.telegraph_link as telegraph_link, recipe.id as id, recipe.title as title, recipe.created_by_user as created_by_user from recipe 
join recipe_ingredient AS ri on ri.recipe_id = recipe.id
where ri.ingredient_id in (' . implode(',', $where) . ') group by ri.recipe_id');
        if (!$recipe_list) {
            $this->getBot()->sendMessage($this->user->chat_id, $this->text['recipe_not_found']);
        } else {
            $found_recipe = 0;
            $recipe_count = 0;
            foreach ($recipe_list as $recipe) {
                if ($recipe->telegraph_link) {
                    $recipe_count++;
                    $right_recipe = true;
                    $recipe_message = '';
                    $recipe_message .= '<a href="' . $recipe->telegraph_link . '">' . $recipe->title . '</a>';

                    $recipe_message .= "\n";
                    $recipe_message .= "\n";
                    $recipe_ingredients = DB::select('select * from recipe_ingredient as ri 
join ingredient AS i on i.id = ri.ingredient_id
where ri.recipe_id = ' . $recipe->id);

                    $i = 0;
                    $ingredient_count = 0;
                    foreach ($recipe_ingredients as $key => $ingredient) {
                        $ingredient_count++;
                        if (in_array($ingredient->id, $where)) {
                            $i++;
                        }
                        if ($ingredient_count == count($recipe_ingredients) && $i < count($where)) {
                            $right_recipe = false;
                            break;
                        }
                        $recipe_message .= $key + 1 . '. ' . $ingredient->title . "\n";
                    }

                    if ($right_recipe) {
                        $found_recipe++;
                        if ($recipe->created_by_user) {
                            $recipe_message .= 'Создатель: ' . $recipe->created_by_user;
                        }

                        $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $recipe_message, new InlineKeyboardMarkup([
                            [[
                                'text' => $this->text['recipe_link'],
                                'url' => strval($recipe->telegraph_link)
                            ]]
                        ]));

                        if ($recipe_count == count($recipe_list)) {
                            $this->getBot()->sendMessage($this->user->chat_id, 'Для нового поиска рецептов нажмите внизу<b> 🔍 Искать рецепты</b>', 'html');
                        }
                    } else {
                        if ($recipe_count == count($recipe_list) && $found_recipe == 0) {
                            $this->getBot()->sendMessage($this->user->chat_id, 'Рецептов с таким набором ингредиентов не найдено! Попробуйте снова <b>(нажмите "Искать рецепт")</b>', 'html');
                        }
                        continue;
                    }
                }
            }
        }

        DB::statement('DELETE FROM search_recipe WHERE user_id = ' . $this->user->id);
    }

}