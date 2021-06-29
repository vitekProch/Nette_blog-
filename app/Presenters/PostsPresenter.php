<?php


namespace App\Presenters;
use Nette\Application\UI\Form;

class PostsPresenter extends BasePresenter
{

    //Zjištění přihlášení
    public function actionCreate(): void
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
    }


    //Přidání proměnných do šablony

    public function actionShow($id)
    {
        $this->template->users = $this->usersModel->getUsers();
        $this->template->post = $this->articleModel->getPublicArticles()->get($id);
        $this->template->comments = $this->commentModel->getPublicComments($id);
        $this->template->author_id = $this->articleModel->getPublicArticles()->get($id)->offsetGet('author_id');
    }


    //Formulář pro přidávní příspěvku

    protected function createComponentPostForm(): Form
    {
        $form = new Form;
        $form->addText('title', 'Titulek:')
            ->setRequired();
        $form->addTextArea('content', 'Obsah:')
            ->setRequired();
        $form->addSubmit('send', 'Uložit a publikovat');
        $form->onSuccess[] = [$this, 'postFormSucceeded'];

        return $form;
    }
    public function postFormSucceeded(Form $form, \stdClass $values): void
    {
        $postId = $this->getParameter('postId');
        $usersId = $this->user->id;

        if ($postId) {
            $post = $this->articleModel->getPublicArticles()->get($postId);
            $post->update([
                'title' => $values->title,
                'content' => $values->content,
                'article_status' => 0,
            ]);
        } else {
            $post = $this->articleModel->getPublicArticles()->insert([
                'author_id' => $usersId,
                'title' => $values->title,
                'content' => $values->content,
            ]);
        }

        $this->flashMessage('Příspěvek byl odeslán ke kontorle.', 'success');
        $this->redirect('Article:article');
    }

    //Úprava článku

    public function actionEdit(int $postId): void
    {
        if(!$this->articleModel->author($this->user->getRoles(),$this->user->id,$this->articleModel->getPublicArticles()->get($postId)->offsetGet('author_id')))
        {
            $this->flashMessage('Nemáš dostatečné oprávnění');
            $this->redirect('Article:article');
        }
        $post = $this->articleModel->getPublicArticles()->get($postId);
        if (!$post) {
            $this->error('Příspěvek nebyl nalezen');
        }
        $this['postForm']->setDefaults($post->toArray());
    }


    //Odstranění článků

    public function handleDelete(int $postId)
    {
        if(!$this->articleModel->author($this->user->getRoles(),$this->user->id,$this->articleModel->getPublicArticles()->get($postId)->offsetGet('author_id')))
        {
            $this->flashMessage('Nemáš dostatečné oprávnění');
            $this->redirect("this");
        }
        $this->articleModel->getPublicArticles()->get($postId)->delete();;

        $this->flashMessage("Smazáno");
        $this->redirect('Article:article');
    }

    //Formůlář pro komentáře

    protected function createComponentCommentForm(): Form
    {
        $form = new Form;

        $form->addText('name', 'Jméno:')
            ->setRequired();

        $form->addEmail('email', 'E-mail:');

        $form->addTextArea('content', 'Komentář:')
            ->setRequired();

        $form->addSubmit('send', 'Publikovat komentář');
        $form->onSuccess[] = [$this, 'commentFormSucceeded'];
        return $form;
    }
    public function commentFormSucceeded(Form $form, \stdClass $values): void
    {
        $postId = $this->getParameter('id');

        $this->commentModel->getPublicComments(1)->insert([
            'post_id' => $postId,
            'name' => $values->name,
            'email' => $values->email,
            'content' => $values->content,
        ]);

        $this->flashMessage('Děkuji za komentář', 'success');

        $this->redirect('this');
    }
}