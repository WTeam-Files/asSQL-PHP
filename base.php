<?php
class Base {
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
        
        $value = null;
        while ($row = $result->fetchArray()) {
            $value = $row['value'];
        }
        
        return $value;
    }

    public function set($key, $value) {
        $query = 'INSERT OR REPLACE INTO key_value (key, value) VALUES (:key, :value);';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':key', $key);
        $statement->bindValue(':value', $value);
        $statement->execute();
    }

    public function delete($key) {
        $query = 'DELETE FROM key_value WHERE key = :key;';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':key', $key);
        $statement->execute();
    }

    public function keys() {
        $query = 'SELECT key FROM key_value;';
        $result = $this->db->query($query);

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

        $exists = ($result->fetchArray() !== false);

        return $exists;
    }
}
// Key-Value Storage for GAYS By @trakoss on Telegram
?>