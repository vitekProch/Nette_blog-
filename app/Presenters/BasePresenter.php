<?php

declare(strict_types=1);

namespace App\Presenters;

use App\Model\ArticleModel;
use App\Model\CommentModel;
use App\Model\UsersModel;
use Nette;
use Nette\Application\UI\Form;


/**
 * Base presenter for all application presenters.
 */
abstract class BasePresenter extends Nette\Application\UI\Presenter
{
    /**
     * @var ArticleModel
     * @inject
     */
    public $articleModel;

    /**
     * @var CommentModel
     * @inject
     */
    public $commentModel;

    /**
     * @var UsersModel
     * @inject
     */
    public $usersModel;

    public function beforeRender()
    {
        // Z databáze si vytáhneme omezenou množinu článků podle výpočtu Paginatoru
        $footer_articles = $this->articleModel->findArticlesByStatus(5,1);

        // kterou předáme do šablony
        $this->template->footer_articles = $footer_articles;
        $this->user;
    }
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
}

