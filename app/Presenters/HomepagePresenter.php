<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\ArticleModel;
use App\Model\ArticleRepository;
use Nette;


class HomepagePresenter extends BasePresenter
{
    public function renderDefault()
    {
        //Úvodní stránka, načtení z databáze 3 nejnovější články
        $articles = $this->articleModel->findArticlesByStatus(3,1);
        $this->template->articles = $articles;
    }
}



