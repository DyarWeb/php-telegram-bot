<?php
declare(strict_types=1);


namespace DyarWeb;
use DyarWeb\Base;

/**
 * Class Get
 * @package DyarWeb
 */
class Get
{
    public static $message_id;
    public static $from_id;
    public static bool $from_is_bot;
    public static $from_first_name;
    public static $from_last_name;
    public static $from_username;
    public static $chat_id;
    public static $chat_title;
    public static $chat_username;
    public static $chat_type;
    public static $text;
    public static $caption;
    public static $new_chat_member_user_id;

    /**
     * @param $input
     */
    public static function set($input)
    {
        self::$message_id = $input->message->message_id;
        self::$from_id = $input->message->from->id;
        self::$from_is_bot = $input->message->from->is_bot;
        self::$from_first_name = $input->message->from->first_name ?? null;
        self::$from_last_name = $input->message->from->last_name ?? null;
        self::$from_username = $input->message->from->username ?? null;
        self::$chat_id = $input->message->chat->id;
        self::$chat_title = $input->message->chat->title;
        self::$chat_username = $input->message->chat->username ?? null;
        self::$chat_type = $input->message->chat->type ?? null;
        self::$text = $input->message->text ?? null;
        self::$caption = $input->message->caption ?? null;
        self::$new_chat_member_user_id = $input->message->new_chat_member->id ?? null;
    }

    /**
     * @param string $file_id
     * @return object
     */
    public function getFile($file_id)
    {
        return Base::sendRequest('getFile', compact('file_id'));
    }

    /**
     * @param string $file_id
     * @param string $file_path
     * @return object|false
     */
    /*public function getFileData($file_id, $file_path)
    {
        return file_get_contents(Base::$baseFileURL . $file_path . '?' . http_build_query(compact('file_id')));
    }*/

    /**
     * @param int $user_id
     * @param int $offset
     * @param int $limit
     * @return object
     */
    public static function getUserProfilePhotos($user_id, $offset = null, $limit = null)
    {
        $params = compact('user_id', 'offset', 'limit');
        return Base::sendRequest('getUserProfilePhotos', $params);
    }
}
