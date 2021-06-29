<?php


namespace App\Presenters;
use Nette;
class ArticlePresenter extends BasePresenter
{
    public function renderArticle(int $page = 1): void
    {
        // Zjistíme si celkový počet publikovaných článků
        $articlesCount = $this->articleModel->getArticleCountByStatus(1);

        // Vyrobíme si instanci Paginatoru a nastavíme jej
        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($articlesCount); // celkový počet článků
        $paginator->setItemsPerPage(10); // počet položek na stránce
        $paginator->setPage($page); // číslo aktuální stránky

        // Z databáze si vytáhneme omezenou množinu článků podle výpočtu Paginatoru
        $articles = $this->articleModel->findArticlesByStatus($paginator->getLength(),1,$paginator->getOffset());
        // kterou předáme do šablony
        $this->template->articles = $articles;
        // a také samotný Paginator pro zobrazení možností stránkování
        $this->template->paginator = $paginator;
    }
}