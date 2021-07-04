<?php
namespace App\Presenters;
use Nette;
use Nette\Application\UI\Form;

class SignPresenter extends BasePresenter
{
    protected function createComponentSignInForm(): Form
    {
        $form = new Form;
        $form->addText('username', 'Uživatelské jméno:')
            ->setRequired('Prosím vyplňte své uživatelské jméno.');

        $form->addPassword('password', 'Heslo:')
            ->setRequired('Prosím vyplňte své heslo.');

        $form->addSubmit('send', 'Přihlásit');

        $form->onSuccess[] = [$this, 'signInFormSucceeded'];
        return $form;


    }
    public function signInFormSucceeded(Form $form, \stdClass $values): void
    {
        try {
            $this->getUser()->login($values->username, $values->password);
            $this->redirect('Homepage:');

        } catch (Nette\Security\AuthenticationException $e) {
            $form->addError('Nesprávné přihlašovací jméno nebo heslo.');
        }
    }
    public function actionCreate(): void
    {
        if (!$this->getUser()->isLoggedIn()) {
            $this->redirect('Sign:in');
        }
    }

    public function actionEdit(int $postId): void
    {
        if (!$this->getUser()->isLoggedIn())
        {
            $this->redirect('Sign:in');
        }
    }
    public function postFormSucceeded(Form $form, array $values): void
    {
        if (!$this->getUser()->isLoggedIn())
        {
            $this->error('Pro vytvoření, nebo editování příspěvku se musíte přihlásit.');
        }

    }
    public function actionOut(): void
    {
        $this->user->logout(true);
        $this->getUser()->logout();
        $this->flashMessage('Odhlášení bylo úspěšné.');
        $this->redirect('Homepage:');
    }


}