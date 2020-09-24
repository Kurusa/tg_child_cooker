<?php

namespace App\Utils;

use TelegramBot\Api\Types\ArrayOfMessageEntity;
use TelegramBot\Api\Types\ArrayOfPhotoSize;
use TelegramBot\Api\Types\Audio;
use TelegramBot\Api\Types\Chat;
use TelegramBot\Api\Types\Contact;
use TelegramBot\Api\Types\Document;
use TelegramBot\Api\Types\Location;
use TelegramBot\Api\Types\Payments\Invoice;
use TelegramBot\Api\Types\Payments\SuccessfulPayment;
use TelegramBot\Api\Types\ReplyKeyboardMarkup;
use TelegramBot\Api\Types\Sticker;
use TelegramBot\Api\Types\User;
use TelegramBot\Api\Types\Venue;
use TelegramBot\Api\Types\Video;
use TelegramBot\Api\Types\Voice;

class Message extends \TelegramBot\Api\Types\Message
{


    /**
     * Reply markup
     *
     * @var ReplyKeyboardMarkup
     */
    protected $reply_markup;

    static protected $map = [
        'message_id' => true,
        'from' => User::class,
        'date' => true,
        'chat' => Chat::class,
        'forward_from' => User::class,
        'forward_date' => true,
        'reply_to_message' => \TelegramBot\Api\Types\Message::class,
        'text' => true,
        'entities' => ArrayOfMessageEntity::class,
        'caption_entities' => ArrayOfMessageEntity::class,
        'audio' => Audio::class,
        'document' => Document::class,
        'photo' => ArrayOfPhotoSize::class,
        'sticker' => Sticker::class,
        'video' => Video::class,
        'voice' => Voice::class,
        'caption' => true,
        'contact' => Contact::class,
        'location' => Location::class,
        'venue' => Venue::class,
        'new_chat_member' => User::class,
        'left_chat_member' => User::class,
        'new_chat_title' => true,
        'new_chat_photo' => ArrayOfPhotoSize::class,
        'delete_chat_photo' => true,
        'group_chat_created' => true,
        'supergroup_chat_created' => true,
        'channel_chat_created' => true,
        'migrate_to_chat_id' => true,
        'migrate_from_chat_id' => true,
        'pinned_message' => Message::class,
        'invoice' => Invoice::class,
        'successful_payment' => SuccessfulPayment::class,
        'forward_signature' => true,
        'author_signature' => true,
        'connected_website' => true,
        'reply_markup' => ReplyKeyboardMarkup::class
    ];

    /**
     * @return ReplyKeyboardMarkup
     */
    public function getReplyMarkup()
    {
        return $this->reply_markup;
    }

    /**
     * @param array $keyboard
     */
    public function setReplyMarkup($keyboard)
    {
        $this->reply_markup = $keyboard;
    }

}