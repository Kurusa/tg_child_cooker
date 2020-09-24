<?php

use App\Services\Status\UserStatusService;

return [
    UserStatusService::RECIPE_TITLE => \App\Commands\AddRecipe\Title::class,
    UserStatusService::RECIPE_LINK => \App\Commands\AddRecipe\Link::class,
    UserStatusService::RECIPE_CREATED_BY => \App\Commands\AddRecipe\CreatedByUser::class,
    UserStatusService::SUPPOSE_RECIPE => \App\Commands\Suppose::class,
    UserStatusService::SUPPOSE_RECIPE_IMAGE => \App\Commands\SupposeImage::class,
];