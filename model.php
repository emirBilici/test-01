<?php

class Model extends Database
{

    /**
     * @param $data
     * @return string
     */
    protected function encryptPassword($data): string
    {
        $method='aes-128-gcm';
        $key = hex2bin('0748BEF58E04D5917ED0B9B558628265');
        $iv = hex2bin('534D5367700114E600102D29');
        $tag = NULL;
        openssl_encrypt($data, $method, $key, OPENSSL_RAW_DATA, $iv, $tag);
        return bin2hex($tag);
    }

    /**
     * @param string $tableName
     * @return bool|array
     */
    protected function GetRows(string $tableName): bool|array
    {
        return $this->db->query('SELECT * FROM ' . $tableName)->fetchAll(PDO::FETCH_OBJ);
    }

    /**
     * @param string $tableName
     * @param string $rowName
     * @param int $id
     * @return mixed
     */
    protected function getRowWithWhere(string $tableName, string $rowName, int $id): mixed
    {
        $query = $this->db->prepare('SELECT * FROM '.$tableName.' WHERE '.$rowName.' = :id');
        $query->execute([
            'id' => $id
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    /**
     * @param string $tableName
     * @param string $rowName
     * @param int $id
     * @return bool
     */
    public function DeleteRow(string $tableName, string $rowName, int $id): bool
    {
        $query = $this->db->prepare('DELETE FROM ' . $tableName . ' WHERE ' . $rowName . ' = :id');
        $delete = $query->execute([
            'id' => $id
        ]);

        if ($delete)
            return true;
        return false;
    }

}