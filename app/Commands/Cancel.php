<?php

namespace App\Commands;

use App\Models\Recipe;
use Illuminate\Database\Capsule\Manager as DB;
use App\Services\Status\UserStatusService;

class Cancel extends BaseCommand
{

    function processCommand()
    {
        switch ($this->user->status) {
            case UserStatusService::RECIPE_LINK:
            case UserStatusService::RECIPE_CREATED_BY:
                $recipe = Recipe::where('status', 'NEW')->where('created_by_admin', $this->user->id)->get();
                if ($recipe) {
                    DB::statement('DELETE FROM recipe_ingredient WHERE recipe_id = ' . $recipe[0]->id);
                    DB::statement('DELETE FROM recipe WHERE id = ' . $recipe[0]->id);
                }
                $this->triggerCommand(MainMenu::class);
                break;
            case UserStatusService::SUPPOSE_RECIPE:
            case UserStatusService::RECIPE_TITLE:
                $this->triggerCommand(MainMenu::class);
                break;
        }
    }

}