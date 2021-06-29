<?php


namespace App\Model;
use App\Repository\CommentRepository;
class CommentModel
{
    /**
     * @var CommentRepository
     */
    private $commentRepository;

    public function __construct(CommentRepository $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }
    public function getPublicComments($id)
    {
        return $this->commentRepository->getPublicComments(5)->where('post_id', $id);
    }
}