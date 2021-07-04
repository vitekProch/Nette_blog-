<?php


namespace App\Repository;
use Nette;

class CommentRepository extends BaseRepository
{
    public const TABLE_NAME_COMMENTS = 'comments';


    public function getPublicComments(int $limit):Nette\Database\Table\Selection
    {
        return $this->database->table(self::TABLE_NAME_COMMENTS)
            ->where('created_at < ', new \DateTime)
            ->limit($limit)
            ->order('created_at DESC');
    }
}