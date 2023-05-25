<?php

class KeyValueStore {
    private $db;
    public function __construct($databaseFile) {
        $this->db = new SQLite3($databaseFile);
        $this->db->exec('PRAGMA journal_mode = WAL;');
        $this->createTable();
    }

    private function createTable() {
        $query = 'CREATE TABLE IF NOT EXISTS key_value (key TEXT PRIMARY KEY, value TEXT);';
        $this->db->exec($query);
    }
    public function get($key) {
        $query = 'SELECT value FROM key_value WHERE key = :key;';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':key', $key);
        $result = $statement->execute();
        
        if (!$result) {
            // تعامل مع خطأ استعلام قاعدة البيانات
            return null;
        }
        
        $value = null;
        while ($row = $result->fetchArray()) {
            $value = $row['value'];
        }
        
        return $value;
    }

    public function set($key, $value) {
        $value = json_encode($value); // تحويل القيمة إلى سلسلة JSON
        
        $query = 'INSERT OR REPLACE INTO key_value (key, value) VALUES (:key, :value);';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':key', $key);
        $statement->bindValue(':value', $value);
        
        $result = $statement->execute();
        if (!$result) {
            // تعامل مع خطأ استعلام قاعدة البيانات
        }
    }

    public function delete($key) {
        $query = 'DELETE FROM key_value WHERE key = :key;';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':key', $key);
        
        $result = $statement->execute();
        if (!$result) {
            // تعامل مع خطأ استعلام قاعدة البيانات
        }
    }

    public function keys() {
        $query = 'SELECT key FROM key_value;';
        $result = $this->db->query($query);

        if (!$result) {
            // تعامل مع خطأ استعلام قاعدة البيانات
            return [];
        }
        
        $keys = [];
        while ($row = $result->fetchArray()) {
            $keys[] = $row['key'];
        }

        return $keys;
    }

    public function exists($key) {
        $query = 'SELECT 1 FROM key_value WHERE key = :key LIMIT 1;';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':key', $key);
        $result = $statement->execute();

        if (!$result) {
            // تعامل مع خطأ استعلام قاعدة البيانات
            return false;
        }
        
        $exists = ($result->fetchArray() !== false);

        return $exists;
    }
}
?>
