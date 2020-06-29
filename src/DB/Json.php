<?php
declare(strict_types=1);

namespace AnarchyService\DB;

/**
 * Class Json
 * @package AnarchyService
 */
class Json
{
    /**
     * @param string $DBName
     * @return bool
     */
    public function CreateDB(string $DBName)
    {
        return mkdir($DBName);
    }

    /**
     * @param string $DBName
     * @param string $TableName
     * @param array $columns
     * @return false|int
     */
    public function CreateTable(string $DBName, string $TableName, array $columns)
    {
        $json = json_encode([$columns]);
        $fileName = $DBName . '/' . $TableName . '.json';
        $res = file_put_contents($fileName, $json);
        $res .= chmod($fileName, 0444);
        return $res;
    }

    /**
     * @param string $DBName
     * @param string $TableName
     * @param array $data
     * @return false|int
     */
    public function InsertData(string $DBName, string $TableName, array $data)
    {
        $fileName = $DBName . '/' . $TableName . '.json';
        $out = json_decode(file_get_contents($fileName), true);
        $out[] = $data;
        return file_put_contents($fileName, json_encode($out));
    }

    /**
     * @param string $DBName
     * @param string $TableName
     * @param array $data
     * @param array $where
     * @return false|int
     */
    public function UpdateData(string $DBName, string $TableName, array $data, array $where)
    {
        $fileName = $DBName . '/' . $TableName . '.json';
        $out = json_decode(file_get_contents($fileName), true);
        foreach ($out as $item) {
            $res = true;
            foreach ($where as $key => $value) {
                if ($item[$key] != $value) {
                    $res = false;
                    break;
                }
            }
            if ($res) foreach ($data as $key => $value) $out[$key] = $value;
        }
        return file_put_contents($fileName, json_encode($out));
    }

    /**
     * @param string $DBName
     * @param string $TableName
     * @param array|null $where
     * @return false|int
     */
    public function DeleteData(string $DBName, string $TableName, array $where)
    {
        $fileName = $DBName . '/' . $TableName . '.json';
        $out = json_decode(file_get_contents($fileName), true);
        foreach ($out as $item) {
            $res = true;
            foreach ($where as $key => $value) {
                if ($item[$key] != $value) {
                    $res = false;
                    break;
                }
            }
            if ($res) unset($out[$item]);
        }
        return file_put_contents($fileName, json_encode($out));
    }

    /**
     * @param string $DBName
     * @param string $TableName
     * @param null $where
     * @return array|mixed
     */
    public function SelectData(string $DBName, string $TableName, $where = null)
    {
        $fileName = $DBName . '/' . $TableName . '.json';
        $out = json_decode(file_get_contents($fileName), true);
        if (!$out || $where == null) return $out;
        else {
            $items = [];
            foreach ($out as $item) {
                $res = true;
                foreach ($where as $key => $value) {
                    if ($item[$key] != $value) {
                        $res = false;
                        break;
                    }
                }
                if ($res) $items[] = $out[$item];
            }
            return $items;
        }
    }
}