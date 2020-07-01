<?php
declare(strict_types=1);

namespace DyarWeb\DB;

/**
 * Class Json
 * @package DyarWeb
 */
class Json
{
    private $DBDir;

    public function __construct()
    {
        $this->DBDir = getenv('jsonDBDir');
    }

    /**
     * @param string $DBName
     * @param string $TableName
     * @param array $columns
     * @param int $mode
     * @return false|int
     */
    public function CreateTable($DBName, $TableName, array $columns, $mode = 600)
    {
        $mode = '0' . $mode;
        if (!is_dir($DBName)) $this->CreateDB($DBName, 0700);
        $json = json_encode([$columns]);
        $fileName = $this->DBDir . '/' . $DBName . '/' . $TableName . '.json';
        $res = file_put_contents($fileName, $json);
        $res .= chmod($fileName, (int)$mode);
        return $res;
    }

    /**
     * @param string $DBName
     * @param int $mode
     * @return bool
     */
    public function CreateDB($DBName, $mode = 700)
    {
        $mode = '0' . $mode;
        if (!is_dir($this->DBDir)) mkdir($this->DBDir, (int)$mode);
        return mkdir($this->DBDir . '/' . $DBName, (int)$mode);
    }

    /**
     * @param $DBName
     * @param $TableName
     * @return bool
     */
    public function DeleteTable($DBName, $TableName)
    {
        return unlink($this->DBDir . '/' . $DBName . '/' . $TableName . '.json');
    }

    /**
     * @param $DBName
     * @return bool
     */
    public function DeleteDB($DBName)
    {
        $files = array_diff(scandir($this->DBDir . '/' . $DBName), ['.', '..']);
        foreach ($files as $file) {
            (is_dir($this->DBDir . '/' . $DBName . '/' . $file)) ? $this->DeleteDB($this->DBDir . '/' . $DBName . '/' . $file) : unlink($this->DBDir . '/' . $DBName . '/' . $file);
        }
        return rmdir($this->DBDir . '/' . $DBName);
    }

    /**
     * @param string $DBName
     * @param string $TableName
     * @param array $data
     * @return false|int
     */
    public function InsertData($DBName, $TableName, array $data)
    {
        $fileName = $this->DBDir . '/' . $DBName . '/' . $TableName . '.json';
        if (!is_file($fileName)) return $this->CreateTable($DBName,$TableName,$data);
        else {
            $out = json_decode(file_get_contents($fileName));
            $out[] = $data;
            return file_put_contents($fileName, json_encode($out));
        }
    }

    /**
     * @param string $DBName
     * @param string $TableName
     * @param array $data
     * @param array $where
     * @return false|int
     */
    public function UpdateData($DBName, $TableName, array $data, array $where)
    {
        $fileName = $this->DBDir . '/' . $DBName . '/' . $TableName . '.json';
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
    public function DeleteData($DBName, $TableName, array $where)
    {
        $fileName = $this->DBDir . '/' . $DBName . '/' . $TableName . '.json';
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
    public function SelectData($DBName, $TableName, $where = null)
    {
        $fileName = $this->DBDir . '/' . $DBName . '/' . $TableName . '.json';
        if (is_file($fileName)) {
            $out = json_decode(file_get_contents($fileName));
            if ($where == null) return $out;
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
        } else return false;
    }

}