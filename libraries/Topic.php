<?php

class Topic
{
    private $db;

    public function __construct() { $this->db = new Database(); }

    public function getAllTopics()
    {
        $this->db->query('SELECT topics.*, users.username, users.avatar, categories.name FROM topics
            INNER JOIN users ON topics.user_id = users.id
            INNER JOIN categories ON topics.category_id = categories.id
            ORDER BY create_date DESC');
        return $this->db->resultset();
    }

    public function getByCategory($category_id)
    {
        $this->db->query('SELECT topics.*, users.username, users.avatar, categories.name FROM topics
            INNER JOIN users ON topics.user_id = users.id
            INNER JOIN categories ON topics.category_id = categories.id
            WHERE topics.category_id = :category_id ORDER BY create_date DESC');
        $this->db->bind(':category_id', (int)$category_id, PDO::PARAM_INT);
        return $this->db->resultset();
    }

    public function getByUser($id)
    {
        $this->db->query('SELECT topics.*, categories.*, users.username, users.avatar FROM topics
            INNER JOIN categories ON topics.category_id = categories.id
            INNER JOIN users ON topics.user_id = users.id
            WHERE topics.user_id = :id ORDER BY create_date DESC');
        $this->db->bind(':id', (int)$id, PDO::PARAM_INT);
        return $this->db->resultset();
    }

    public function getTotalTopics()
    {
        $this->db->query('SELECT COUNT(*) AS total FROM topics');
        return (int)$this->db->single()['total'];
    }

    public function getTotalCategories()
    {
        $this->db->query('SELECT COUNT(*) AS total FROM categories');
        return (int)$this->db->single()['total'];
    }

    public function getTotalReplies($topic_id)
    {
        $this->db->query('SELECT COUNT(*) AS total FROM replies WHERE topic_id = :topic_id');
        $this->db->bind(':topic_id', (int)$topic_id, PDO::PARAM_INT);
        return (int)$this->db->single()['total'];
    }

    public function getCategory($category_id)
    {
        $this->db->query('SELECT * FROM categories WHERE id = :category_id');
        $this->db->bind(':category_id', (int)$category_id, PDO::PARAM_INT);
        return $this->db->single();
    }

    public function getTopic($id)
    {
        $this->db->query('SELECT topics.*, users.username, users.name, users.avatar FROM topics
            INNER JOIN users ON topics.user_id = users.id WHERE topics.id = :id');
        $this->db->bind(':id', (int)$id, PDO::PARAM_INT);
        return $this->db->single();
    }

    public function getReplies($id)
    {
        $this->db->query('SELECT replies.*, users.* FROM replies
            INNER JOIN users ON replies.user_id = users.id
            WHERE replies.topic_id = :id ORDER BY create_date ASC');
        $this->db->bind(':id', (int)$id, PDO::PARAM_INT);
        return $this->db->resultset();
    }

    public function create($data)
    {
        $this->db->query('INSERT INTO topics (category_id, user_id, title, body)
            VALUES (:category_id, :user_id, :title, :body)');
        $this->db->bind(':category_id', (int)$data['category_id'], PDO::PARAM_INT);
        $this->db->bind(':user_id', (int)$data['user_id'], PDO::PARAM_INT);
        $this->db->bind(':title', trim($data['title']));
        $this->db->bind(':body', trim($data['body']));
        return $this->db->execute();
    }

    public function reply($data)
    {
        $this->db->query('INSERT INTO replies (topic_id, user_id, body)
            VALUES (:topic_id, :user_id, :body)');
        $this->db->bind(':topic_id', (int)$data['topic_id'], PDO::PARAM_INT);
        $this->db->bind(':user_id', (int)$data['user_id'], PDO::PARAM_INT);
        $this->db->bind(':body', trim($data['body']));
        return $this->db->execute();
    }
}
