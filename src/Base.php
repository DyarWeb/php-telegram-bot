<?php
declare(strict_types=1);

namespace AnarchyService;

use AnarchyService\BotException;

/**
 * Class Base
 */
class Base
{
    private const BASE_URL = 'https://api.telegram.org';
    private const BOT_URL = '/bot';
    private const FILE_URL = '/file';

    private string $token;
    private static string $baseURL;
    private static string $baseFileURL;

    /**
     * Base constructor.
     * @throws BotException
     */
    public function __construct()
    {
        $this->token = getenv('TOKEN');
        if (!$this->token) {
            throw new BotException("Token can\'t be empty");
        }
        self::$baseURL = self::BASE_URL . self::BOT_URL . $this->token . '/';
        self::$baseFileURL = self::BASE_URL . self::FILE_URL . self::BOT_URL . $this->token . '/';
    }

    /**
     * @param int $user_id
     * @param int $offset
     * @param int $limit
     * @return mixed
     */
    public function getUserProfilePhotos($user_id, $offset = null, $limit = null)
    {
        $params = compact('user_id', 'offset', 'limit');

        return self::sendRequest('getUserProfilePhotos', $params);
    }

    /**
     * @param string $method
     * @param array $params
     * @return mixed
     */
    public static function sendRequest($method, $params)
    {
        return json_decode(file_get_contents(self::$baseURL . $method . '?' . http_build_query($params)), true);
    }

    /**
     * @param string $file_id
     * @return mixed
     */
    public function getFile($file_id)
    {
        return self::sendRequest('getFile', compact('file_id'));
    }

    /**
     * @param string $file_id
     * @param string $file_path
     * @return mixed
     */
    public function getFileData($file_id, $file_path)
    {
        return file_get_contents(self::$baseFileURL . $file_path . '?' . http_build_query(compact('file_id')));
    }

    /**
     * @param string $method
     * @param string $data
     * @return mixed
     * @throws \AnarchyService\BotException
     */
    protected function uploadFile($method, $data)
    {
        $key = [
            'sendPhoto' => 'photo',
            'sendAudio' => 'audio',
            'sendDocument' => 'document',
            'sendSticker' => 'sticker',
            'sendVideo' => 'video',
            'setWebhook' => 'certificate'
        ];

        if (filter_var($data[$key[$method]], FILTER_VALIDATE_URL)) {
            $file = __DIR__ . DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . mt_rand(0, 9999);

            $url = true;
            file_put_contents($file, file_get_contents($data[$key[$method]]));
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $file);

            $extensions = [
                'image/jpeg' => '.jpg',
                'image/png' => '.png',
                'image/gif' => '.gif',
                'image/bmp' => '.bmp',
                'image/tiff' => '.tif',
                'audio/ogg' => '.ogg',
                'audio/mpeg' => '.mp3',
                'video/mp4' => '.mp4',
                'image/webp' => '.webp'
            ];

            if ($method != 'sendDocument') {
                if (!array_key_exists($mime_type, $extensions)) {
                    unlink($file);
                    throw new BotException('extensions not supported');
                }
            }

            $newFile = $file . $extensions[$mime_type];
            rename($file, $newFile);
            $data[$key[$method]] = new CurlFile($newFile, $mime_type, $newFile);
        } else {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $data[$key[$method]]);
            $data[$key[$method]] = new CurlFile($data[$key[$method]], $mime_type, $data[$key[$method]]);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, self::$baseURL . $method);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        $response = json_decode(curl_exec($ch), true);

        if ($url) {
            unlink($newFile);
        }

        return $response;
    }
}