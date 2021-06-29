<?php


namespace App\Presenters;


use App\Forms\FormFactory;
use App\Model\ArticleRepository;
use App\Model\UserManager;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\ComponentModel\IComponent;
use Nette;

class RegistrationPresenter extends BasePresenter
{
    private $userManager;
    private $registrationForm;

    public function __construct(UserManager $userManager,FormFactory $registrationForm)
    {
        $this->userManager = $userManager;
        $this->registrationForm = $registrationForm;
    }


    //Registrační formulář

    protected function createComponentRegistrationForm(): Form
    {
        $form = $this->registrationForm->create();
        $form = new Form;
        $form->addText('username', 'Uživatelské jméno: ')
            ->setRequired('Prosím, vyplňte své uživatelské jméno.');
        $form->addPassword('password', 'Heslo: ');
        $form->addPassword('passwordVerify', 'Heslo pro kontrolu:')
            ->setRequired('Zadejte prosím heslo ještě jednou pro kontrolu')
            ->addRule($form::EQUAL, 'Hesla se neshodují', $form['password'])
            ->setOmitted();

        $form->addSubmit('send', 'Registrovat');
        $form->onSuccess[] = [$this, 'formSucceeded'];
        return $form;

    }
    public function formSucceeded(Form $form, $data): void
    {

        $this->userManager->add($data->username,$data->password);
        $this->flashMessage('Byl jste úspěšně registrován.');
        $this->redirect('Homepage:');

    }

}