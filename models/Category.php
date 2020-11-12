<?php

class Category {
    private $conn;
    private $table = 'categories';

    public $id;
    public $name;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "
            SELECT *
            FROM {$this->table}
            ORDER BY name ASC";

        $statement = $this->conn->prepare($query);
        $statement->execute();

        return $statement;
    }

    public function create() {
        $query = "
            INSERT INTO {$this->table}
            SET name = :name";
                
        $statement = $this->conn->prepare($query);

        // clean data
        $this->name = htmlspecialchars(strip_tags($this->name));

        $statement->bindParam(':name', $this->name);

        if ($statement->execute()) {
            return true;
        }

        printf("Error: %s.\n", $statement->error);

        return false;
    }

    public function update() {
        $query = "
            UPDATE {$this->table}
            SET 
                name = :name,
            WHERE id = :id";

        $statement = $this->conn->prepare($query);

        // clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->name = htmlspecialchars(strip_tags($this->name));

        $statement->bindParam(':id', $this->id);
        $statement->bindParam(':name', $this->name);

        if ($statement->execute()) {
            return true;
        }

        printf("Error: %s.\n", $statement->error);

        return false;
    }

    private function exists()
    {
        $query = "SELECT COUNT(id) as NUM FROM {$this->table} WHERE id = :id";
        $statement = $this->conn->prepare($query);

        $this->id = (int) htmlspecialchars(strip_tags($this->id));
        $statement->bindParam(':id', $this->id);

        $statement->execute();
        return (bool) ((int) ($statement->fetch(PDO::FETCH_ASSOC))['NUM']);
    }

    public function delete() {
        if (!$this->exists()) {
            return false;
        }

        $query = "DELETE FROM {$this->table} WHERE id = :id";
        $statement = $this->conn->prepare($query);

        $this->id = (int) htmlspecialchars(strip_tags($this->id));
        $statement->bindParam(':id', $this->id);

        if ($statement->execute()) {
            return true;
        }

        printf("Error: %s.\n", $statement->error);

        return false;
    }
}