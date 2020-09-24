<?php
return [
    'ing' => \App\Commands\AddRecipe\Ingredient::class,
    'ing_done' => \App\Commands\AddRecipe\Link::class,
    'search_ing' => \App\Commands\SearchRecipe::class,
    'next_ingredient' => \App\Commands\SearchRecipe::class,
    'next_ingredient_end' => \App\Commands\SearchRecipe::class,
    'prev_ingredient_end' => \App\Commands\SearchRecipe::class,
    'prev_ingredient' => \App\Commands\SearchRecipe::class,
    'search_ing_done' => \App\Commands\SearchRecipeDone::class,
    'recipe_done' => \App\Commands\RecipeModerate::class,
    'recipe_decline' => \App\Commands\RecipeModerate::class,
];