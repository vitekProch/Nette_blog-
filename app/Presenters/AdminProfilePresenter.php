<?php


namespace App\Presenters;
use Nette;
use Nette\Application\UI\Form;

class AdminProfilePresenter extends BasePresenter
{
    public function startup()
    {
        parent::startup();
        // redirect if not logged in
        $role = $this->user->getIdentity()->getData()['role'];
        if (!$this->user->isAllowed('adminPage', 'view')) {
            $this->flashMessage('Přístup odmítnut!');
            $this->redirect('Homepage:Default');
        }
    }
    public function renderAdmin()
    {
        $users_value = $this->usersModel->getUsers();
        $this->template->users_value = $users_value;
    }
    public function renderEdit(int $id): void
    {
        $users_value = $this->usersModel->getUsers();
        $this->template->users_value = $users_value;
        $this->template->user_id = $this->usersModel->getUsers()->get($id);
    }
    public function actionEdit(int $id): void
    {
        if(!$this->getUser()->isAllowed('adminPage', 'edit'))
        {
            $this->flashMessage('Nemáš dostatečné oprávnění');
            $this->redirect('AdminProfile:admin');
        }

        $users_value = $this->usersModel->getUsers()->get($id);
        if (!$users_value) {
            $this->error('Uživatel nebyl nalezen');
        }
        $this['editForm']->setDefaults($users_value->toArray());
    }


    public function handleDelete($id)
    {
        if(!$this->getUser()->isAllowed('adminPage', 'delete'))
        {
            $this->flashMessage('Nemáš dostatečné oprávnění');
            $this->redirect('AdminProfile:admin');
        }
        $userId = $this->usersModel->deleteUsers()->get($id);
        $userId->delete();

        $this->flashMessage("Smazáno");
        $this->redirect("this");
    }


    protected function createComponentEditForm(): Form
    {
        $form = new Form;
        $form->addText('role', 'Role:')
            ->setRequired();
        $form->addSubmit('save', 'Uložit');
        $form->onSuccess[] = [$this, 'editFormSucceeded'];

        return $form;
    }
    public function editFormSucceeded(Form $form, array $values): void
    {
        $id = $this->getParameter('id');

        if ($id) {
            $id = $this->usersModel->getUsers()->get($id);
            $id->update($values);
        } else {
            $id = $this->usersModel->getUsers()->insert($values);
        }

        $this->flashMessage('Změna byla uložena', 'success');
        $this->redirect('AdminProfile:admin');
    }
}