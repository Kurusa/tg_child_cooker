<?php

namespace App\Commands;

use App\Services\Status\UserStatusService;
use TelegramBot\Api\Types\Inline\InlineKeyboardMarkup;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;

class SupposeImage extends BaseCommand
{

    function processCommand()
    {
        if ($this->user->status == UserStatusService::SUPPOSE_RECIPE_IMAGE) {
            if ($this->update->getMessage()->getPhoto()) {
                $file_id = $this->update->getMessage()->getPhoto()[0]->getFileId();
                \App\Models\Suppose::where('user_id', $this->user->id)->where('moderated', 0)->update([
                    'image' => $file_id
                ]);
            }
            $new_recipe = \App\Models\Suppose::where('user_id', $this->user->id)->where('moderated', 0)->orderBy('created_at', 'desc')->take(1)->get();

            $admin_list = explode(',', env('ADMIN_LIST'));

            $this->getBot()->sendMessage($this->user->chat_id, $this->text['recipe_accepted']);
            $this->triggerCommand(MainMenu::class);

            foreach ($admin_list as $admin) {
                if ($new_recipe[0]->image) {
                    $this->getBot()->sendPhoto($admin, $file_id, $new_recipe[0]->text . "\n" . 'Рецепт от: <a href="tg://user?id=' . $this->user->chat_id . '">' . $this->user->first_name . '</a>', null, new InlineKeyboardMarkup([
                        [
                            [
                                'text' => 'принять',
                                'callback_data' => json_encode([
                                    'a' => 'recipe_done',
                                    'id' => $new_recipe[0]->id
                                ])
                            ],
                            [
                                'text' => 'отклонить',
                                'callback_data' => json_encode([
                                    'a' => 'recipe_decline',
                                    'id' => $new_recipe[0]->id
                                ])
                            ],
                        ]
                    ]), false, 'html');
                } else {
                    $this->getBot()->sendMessageWithKeyboard($admin, $new_recipe[0]->text . "\n" . 'Рецепт от: <a href="tg://user?id=' . $this->user->chat_id . '">' . $this->user->first_name . '</a>',
                        new InlineKeyboardMarkup([
                            [
                                [
                                    'text' => 'принять',
                                    'callback_data' => json_encode([
                                        'a' => 'recipe_done',
                                        'id' => $new_recipe[0]->id
                                    ])
                                ],
                                [
                                    'text' => 'отклонить',
                                    'callback_data' => json_encode([
                                        'a' => 'recipe_decline',
                                        'id' => $new_recipe[0]->id
                                    ])
                                ],
                            ]
                        ]));
                }
            }
        } else {
            $this->user->status = UserStatusService::SUPPOSE_RECIPE_IMAGE;
            $this->user->save();

            $buttons = [
                [$this->text['skip']], [$this->text['cancel']]
            ];
            $this->getBot()->sendMessageWithKeyboard($this->user->chat_id, $this->text['write_recipe_image'], new ReplyKeyboardMarkup($buttons, false, true));
        }
    }

}