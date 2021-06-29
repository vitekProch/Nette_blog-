<?php


namespace App\Repository;


class CommentRepository extends BaseRepository
{
    public function getPublicComments(int $limit)
    {
        return $this->database->table('comments')
            ->where('created_at < ', new \DateTime)
            ->limit($limit)
            ->order('created_at DESC');
    }
}