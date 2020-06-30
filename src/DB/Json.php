<?php
declare(strict_types=1);

namespace AnarchyService\DB;

/**
 * Class Json
 * @package AnarchyService
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
     * @param int $mode
     * @return bool
     */
    public function CreateDB(string $DBName , $mode = 755)
    {
        $permission = 0;
        $permission .= $mode;
        if (!is_dir($this->DBDir)) mkdir($this->DBDir,$permission);
        return mkdir($this->DBDir.'/'.$DBName ,$permission);
    }

    /**
     * @param string $DBName
     * @param string $TableName
     * @param array $columns
     * @param int $mode
     * @return false|int
     */
    public function CreateTable(string $DBName, string $TableName, array $columns , $mode = 444)
    {
        $permission = 0;
        $permission .= $mode;
        if (!is_dir($DBName)) $this->CreateDB($DBName,755);
        $json = json_encode([$columns]);
        $fileName = $this->DBDir.'/'.$DBName . '/' . $TableName . '.json';
        $res = file_put_contents($fileName, $json);
        $res .= chmod($fileName, $permission);
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
        $fileName = $this->DBDir.'/'.$DBName . '/' . $TableName . '.json';
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
        $fileName = $this->DBDir.'/'.$DBName . '/' . $TableName . '.json';
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
        $fileName = $this->DBDir.'/'.$DBName . '/' . $TableName . '.json';
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
        $fileName = $this->DBDir.'/'.$DBName . '/' . $TableName . '.json';
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