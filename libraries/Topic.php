<?php

class Topic
{
    private $db;

    public function __construct() { $this->db = new Database(); }

    private function searchClause($search, &$params)
    {
        $search = trim((string)$search);
        if ($search === '') return '';
        $params[':search_title'] = '%' . $search . '%';
        $params[':search_body'] = '%' . $search . '%';
        $params[':search_user'] = '%' . $search . '%';
        $params[':search_category'] = '%' . $search . '%';
        return ' AND (topics.title LIKE :search_title OR topics.body LIKE :search_body OR users.username LIKE :search_user OR categories.name LIKE :search_category)';
    }

    private function fetchTopics($where = '', $bindings = [])
    {
        $sql = 'SELECT topics.*, users.username, users.avatar, categories.name
                FROM topics
                INNER JOIN users ON topics.user_id = users.id
                INNER JOIN categories ON topics.category_id = categories.id ' . $where . '
                ORDER BY topics.create_date DESC, topics.id DESC';
        $this->db->query($sql);
        foreach ($bindings as $key => $value) $this->db->bind($key, $value);
        return $this->db->resultset();
    }

    public function getAllTopics($search = '')
    {
        $params = [];
        $clause = $this->searchClause($search, $params);
        if ($clause !== '') $clause = 'WHERE ' . substr($clause, 5);
        return $this->fetchTopics($clause, $params);
    }

    public function getByCategory($category_id, $search = '')
    {
        $params = [':category_id' => (int)$category_id];
        $clause = 'WHERE topics.category_id = :category_id';
        $clause .= $this->searchClause($search, $params);
        return $this->fetchTopics($clause, $params);
    }

    public function getByUser($id, $search = '')
    {
        $params = [':user_id' => (int)$id];
        $clause = 'WHERE topics.user_id = :user_id';
        $clause .= $this->searchClause($search, $params);
        return $this->fetchTopics($clause, $params);
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
        $this->db->query('SELECT topics.*, users.username, users.name, users.avatar, categories.name AS category_name
            FROM topics
            INNER JOIN users ON topics.user_id = users.id
            INNER JOIN categories ON topics.category_id = categories.id
            WHERE topics.id = :id LIMIT 1');
        $this->db->bind(':id', (int)$id, PDO::PARAM_INT);
        return $this->db->single();
    }

    public function getReplies($id)
    {
        $this->db->query('SELECT replies.*, users.username, users.name, users.avatar
            FROM replies
            INNER JOIN users ON replies.user_id = users.id
            WHERE replies.topic_id = :id ORDER BY replies.create_date ASC, replies.id ASC');
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
