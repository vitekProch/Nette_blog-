<?php


namespace App\Presenters;

use Nette;

class EditorProfilePresenter extends BasePresenter
{
    public function startup()
    {
        parent::startup();
        // redirect if not logged in
        if (!$this->user->isAllowed('editorPage', 'view')) {
            $this->flashMessage('Přístup odmítnut!');
            $this->redirect('Homepage:Default');
        }
    }

    public function actionShow($id)
    {
        $this->template->post = $this->articleModel->getPublicArticles()->get($id);
    }

    public function actionAccept(int $page = 1)
    {
        // Zjistíme si celkový počet publikovaných článků
        $articlesCount = $this->articleModel->getArticleCountByStatus(0);

        // Vyrobíme si instanci Paginatoru a nastavíme jej
        $paginator = new Nette\Utils\Paginator;
        $paginator->setItemCount($articlesCount); // celkový počet článků
        $paginator->setItemsPerPage(10); // počet položek na stránce
        $paginator->setPage($page); // číslo aktuální stránky

        // Z databáze si vytáhneme omezenou množinu článků podle výpočtu Paginatoru
        $pedingArticle = $this->articleModel->findArticlesByStatus($paginator->getLength(),0,$paginator->getOffset());
        // a také samotný Paginator pro zobrazení možností stránkování
        $this->template->paginator = $paginator;
        $this->template->pedingArticle = $pedingArticle;
    }

    public function handleApproved($postid)
    {
        if(!$this->getUser()->isAllowed('article', 'delete'))
        {
            $this->flashMessage('Nemáš dostatečné oprávnění');
            $this->redirect("this");
        }
        $post = $this->articleModel->getPublicArticles()->get($postid);
        $post->update([
            'article_status' => 1,
        ]);
        $this->redirect('EditorProfile:accept');
    }
    public function handleDeny(int $postid)
    {
        if (!$this->getUser()->isAllowed('article', 'delete')) {
            $this->flashMessage('Nemáš dostatečné oprávnění');
            $this->flashMessage('Položka byla zamítnuta.');
            $this->redirect("this");
        }
        $post = $this->articleModel->getPublicArticles()->get($postid);
        $post->update([
            'article_status' => 2,
        ]);
        $this->redirect('EditorProfile:accept');
    }

}