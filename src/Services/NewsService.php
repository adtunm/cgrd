<?php

namespace App\Services;

use App\Database\Connection;
use App\Database\Models\NewsModel;
use App\Routing\Responses\RedirectResponse;
use App\Routing\Responses\ResponseInterface;
use App\Session\Session;
use PDO;

class NewsService
{
    private Connection $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function save(NewsModel $news): bool
    {
        $query = <<<SQL
            insert into news(title, content) VALUES (:title, :content);
        SQL;

        $stmt = $this->db->getConnection()->prepare($query);

        $stmt->bindParam(':title', $news->title, PDO::PARAM_STR);
        $stmt->bindParam(':content', $news->content, PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function get(int $id): ?NewsModel
    {
        $query = <<<SQL
            select * from news where id = :id
        SQL;

        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchObject(NewsModel::class) ?: null;

    }

    public function getAll(): array
    {
        $query = <<<SQL
            select * from news
        SQL;

        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS  ,NewsModel::class);

    }

    public function delete(int $id): bool
    {
        $query = <<<SQL
            delete from news where id = :id
        SQL;

        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function edit(NewsModel $news)
    {
        $query = <<<SQL
            update news set content = :content, title = :title where id = :id
        SQL;

        $stmt = $this->db->getConnection()->prepare($query);
        $stmt->bindParam(':content', $news->content, PDO::PARAM_STR);
        $stmt->bindParam(':title', $news->title, PDO::PARAM_STR);
        $stmt->bindParam(':id', $news->id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }

    public function getResponse($news): array
    {
        if ($news !== null) {
            $response = [
                'status' => 'OK',
                'id' => $news->id,
                'title' => $news->title,
                'content' => $news->content,
            ];
        } else {
            $response = [
                'status' => 'ERROR',
            ];
        }

        return $response;
    }

    public function checkErrors(\stdClass $data): ?string
    {
        if (empty($data->title) || empty($data->content)) {
            return 'Title and content are necessary';
        }

        if (strlen($data->title) > 255) {
            return 'Title is too long. Max length is 255 characters';
        }

        return null;
    }

    public function isNewsSame(NewsModel $news): bool
    {
        $old = $this->get($news->id);

        return strcmp($old->title, $news->title) === 0 && strcmp($old->content, $news->content) === 0;
    }
}