<?php

namespace App\Utils;

use TelegramBot\Api\BotApi;
use TelegramBot\Api\Types\Message;

class Api extends BotApi
{

    public function __construct($token, $trackerToken = null)
    {
        parent::__construct($token, $trackerToken);
    }

    public function sendMessageWithKeyboard($chat_id, string $text, $keyboard)
    {
        return $this->sendMessage($chat_id, $text, 'HTML', false, null, $keyboard);
    }

    /**
     * Use this method to send text messages. On success, the sent \TelegramBot\Api\Types\Message is returned.
     *
     * @param int|string $chatId
     * @param string $text
     * @param string|null $parseMode
     * @param bool $disablePreview
     * @param int|null $replyToMessageId
     * @param Types\ReplyKeyboardMarkup|Types\ReplyKeyboardHide|Types\ForceReply|
     *        Types\ReplyKeyboardRemove|null $replyMarkup
     * @param bool $disableNotification
     *
     * @return \TelegramBot\Api\Types\Message
     * @throws \TelegramBot\Api\InvalidArgumentException
     * @throws \TelegramBot\Api\Exception
     */
    public function sendMessage(
        $chatId,
        $text,
        $parseMode = null,
        $disablePreview = false,
        $replyToMessageId = null,
        $replyMarkup = null,
        $disableNotification = false
    )
    {
        try {
            return Message::fromResponse($this->call('sendMessage', [
                'chat_id' => $chatId,
                'text' => $text,
                'parse_mode' => $parseMode,
                'disable_web_page_preview' => $disablePreview,
                'reply_to_message_id' => (int)$replyToMessageId,
                'reply_markup' => is_null($replyMarkup) ? $replyMarkup : $replyMarkup->toJson(),
                'disable_notification' => (bool)$disableNotification,
            ]));
        } catch (\Exception $e) {
            error_log($e->getMessage());
        }
    }
}