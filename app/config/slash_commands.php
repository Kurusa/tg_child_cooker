<?php
return [
	'/start' => \App\Commands\MainMenu::class,
	'/add_ingredient' => \App\Commands\AddIngredient::class,
	'/delete_ingredient' => \App\Commands\DeleteIngredient::class,
	'/ingredient_list' => \App\Commands\IngredientList::class,
];