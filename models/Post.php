<?php

class Post {
    private $conn;
    private $table = 'posts';

    public $id;
    public $category_id;
    public $category_name;
    public $title;
    public $body;
    public $author;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function read() {
        $query = "
            SELECT 
                cat.name as category_name,
                post.id,
                post.category_id,
                post.title,
                post.body,
                post.author,
                post.created_at
            FROM {$this->table} post
            LEFT JOIN categories as cat
            ON post.category_id = cat.id
            ORDER BY post.created_at DESC";

        $statement = $this->conn->prepare($query);
        $statement->execute();

        return $statement;
    }

    public function readOne() {
        $query = "
            SELECT 
                cat.name as category_name,
                post.id,
                post.category_id,
                post.title,
                post.body,
                post.author,
                post.created_at
            FROM {$this->table} post
            LEFT JOIN categories as cat
            ON post.category_id = cat.id
            WHERE post.id = ? 
            LIMIT 0, 1";

        $statement = $this->conn->prepare($query);

        $statement->bindParam(1, $this->id);
        $statement->execute();

        $row = $statement->fetch(PDO::FETCH_ASSOC);
        
        $this->title = $row['title'];
        $this->body = html_entity_decode($row['body']);
        $this->author = $row['author'];
        $this->category_id = $row['category_id'];
        $this->category_name = $row['category_name'];
    }

    public function create() {
        $query = "
            INSERT INTO {$this->table}
            SET 
                title = :title,
                body = :body,
                author = :author,
                category_id = :category_id";
                
        $statement = $this->conn->prepare($query);

        // clean data
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = (int) htmlspecialchars(strip_tags($this->category_id));
        $this->id = htmlspecialchars(strip_tags($this->id));

        $statement->bindParam(':title', $this->title);
        $statement->bindParam(':body', $this->body);
        $statement->bindParam(':author', $this->author);
        $statement->bindParam(':category_id', $this->category_id);

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
                title = :title,
                body = :body,
                author = :author,
                category_id = :category_id
            WHERE id = :id";

        $statement = $this->conn->prepare($query);

        // clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->title = htmlspecialchars(strip_tags($this->title));
        $this->body = htmlspecialchars(strip_tags($this->body));
        $this->author = htmlspecialchars(strip_tags($this->author));
        $this->category_id = (int) htmlspecialchars(strip_tags($this->category_id));

        $statement->bindParam(':title', $this->title);
        $statement->bindParam(':body', $this->body);
        $statement->bindParam(':author', $this->author);
        $statement->bindParam(':category_id', $this->category_id);
        $statement->bindParam(':id', $this->id);

        if ($statement->execute()) {
            return true;
        }

        printf("Error: %s.\n", $statement->error);

        return false;
    }

    public function delete() {
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