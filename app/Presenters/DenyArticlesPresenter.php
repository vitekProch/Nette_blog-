<?php


namespace App\Presenters;
use Nette;

class DenyArticlesPresenter extends BasePresenter
{
    public function startup()
    {
        parent::startup();
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
    }
    public function renderDeny(int $page = 1)
    {
        // Zjistíme si celkový počet publikovaných článků
        $articlesCount = $this->articleModel->getUserArticleCountByStatus(2,$this->user->id);

        // Vyrobíme si instanci Paginatoru a nastavíme jej
        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($articlesCount); // celkový počet článků
        $paginator->setItemsPerPage(10); // počet položek na stránce
        $paginator->setPage($page); // číslo aktuální stránky

        // Z databáze si vytáhneme omezenou množinu článků podle výpočtu Paginatoru
        $denyArticle = $this->articleModel->findUserArticlesByStatus($this->user->id, 2, $paginator->getLength(),$paginator->getOffset());
        // a také samotný Paginator pro zobrazení možností stránkování
        $this->template->paginator = $paginator;
        $this->template->denyArticle = $denyArticle;
    }
}