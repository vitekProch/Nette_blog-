<?php


namespace App\Model;

use App\Repository\ArticleRepository;
use Nette\Utils\Arrays;
use Nette;

class ArticleModel
{
    /**
     * @var ArticleRepository
     * @inject
     */
    public $articleRepository;

    /**
     * @var Arrays;
     * @inject
     */
    public $arrays;


    public function __construct(ArticleRepository $articleRepository)
    {
        $this->articleRepository = $articleRepository;
    }

    public function getPublicArticles(): Nette\Database\Table\Selection
    {
        return $this->articleRepository->getPublicArticles();
    }

    /**
     * Vyhledávání
     */
    public function findArticlesBySearch($search_value): Nette\Database\Table\Selection
    {
        return $this->articleRepository->findArticlesBySearch($search_value);
    }

    /**
     * Články ke schválení
     */

    /**
     * @param int $status Status článku: 0-čekající, 1-schvaleno, 2-zamitnuto
     */
    public function findArticlesByStatus($limit,$status ,int $offset=0): Nette\Database\ResultSet
    {
        return $this->articleRepository->findArticlesByStatus($limit,$status,$offset);
    }
    public function getArticleCountByStatus($status): int
    {
        return $this->articleRepository->getArticleCountByStatus($status);
    }
    public function findUserArticlesByStatus($id, $status, $limit, $offset): \Nette\Database\ResultSet
    {
        return $this->articleRepository->findUserArticlesByStatus($id, $status, $limit, $offset);
    }
    public function getUserArticleCountByStatus(int $status, int $id): int
    {
        return $this->articleRepository->getUserArticleCountByStatus($status, $id);
    }

    public function author($role, $author_id, $article_id): int
    {
        if ($author_id === $article_id or ($role === ['editor']))
        {
            return true;
        }
        return false;
    }

}