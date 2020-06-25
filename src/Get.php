<?php
declare(strict_types=1);


namespace AnarchyService;
use AnarchyService\Base;

/**
 * Class Get
 * @package AnarchyService
 */
class Get
{

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