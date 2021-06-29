<?php


namespace App\Repository;
use Nette;

class ArticleRepository extends BaseRepository
{
    public const TABLE_NAME_POSTS = 'posts';


    public function getPublicArticles(): Nette\Database\Table\Selection
    {
        return $this->database->table(self::TABLE_NAME_POSTS)
            ->where('created_at < ', new \DateTime)
            ->order('created_at DESC');
    }
    public function findArticlesBySearch($searchPhrase): Nette\Database\Table\Selection
    {
        return $this->database->table(self::TABLE_NAME_POSTS)
            ->where("title LIKE ? OR content LIKE ?","%".$searchPhrase."%","%".$searchPhrase."%")
            ->where('article_status = 1');
    }
    public function deleteArticle($postId)
    {
        return $this->database->table(self::TABLE_NAME_POSTS)->get($postId)->delete();
    }

    /**
     * Články čekající na schválení
     */

    public function findArticlesByStatus(int $limit, int $status, int $offset = 0): Nette\Database\ResultSet
    {
        return $this->database->query('
			SELECT * FROM posts
			WHERE created_at < ?
			AND article_status = ?
			ORDER BY created_at DESC
			LIMIT ?
			OFFSET ?',
            new \DateTime, $status, $limit, $offset
        );
    }
    public function getArticleCountByStatus(int $status): int
    {
        return $this->database->fetchField('
        SELECT COUNT(*) 
        FROM posts 
        WHERE created_at < ? 
        AND article_status = ?',
            new \DateTime,$status);
    }
    public function getUserArticleCountByStatus(int $status, int $id): int
    {
        return $this->database->fetchField('SELECT COUNT(*) 
        FROM posts 
        WHERE created_at < ? 
        AND article_status = ? 
        AND author_id = ?',
            new \DateTime,$status,$id);
    }

    public function findUserArticlesByStatus($id, int $status, int $limit, int $offset = 0): Nette\Database\ResultSet
    {
        return $this->database->query('
			SELECT * FROM posts
			WHERE created_at < ?
			AND article_status = ?
			AND author_id = ?
			ORDER BY created_at DESC
			LIMIT ?
			OFFSET ?',
            new \DateTime, $status, $id, $limit, $offset
        );
    }
}
