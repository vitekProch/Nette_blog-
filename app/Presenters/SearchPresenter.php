<?php


namespace App\Presenters;
use Nette;
use Nette\Application\UI\Form;

class SearchPresenter extends BasePresenter
{
    protected function createComponentSearchForm()
    {
        $form = new Form;
        $form->addText('search_value', 'Hledat:')
            ->setRequired(TRUE);
        $form->addSubmit('send', 'Search');
        $form->onSuccess[] = [$this, 'searchFormSucceeded'];
        return $form;
    }
    public function searchFormSucceeded(Form $form, $values): void
    {
        $this->redirect("Search:search", [$values->search_value]);
    }
    public function actionSearch($search_value)
    {
        $articles = $this->articleModel->findArticlesBySearch($search_value);
        $this->template->articles = $articles;
    }
}