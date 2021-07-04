<?php


namespace App\Model;

use App\Repository\UsersRepository;
use Nette;

class UsersModel
{
    /**
     * @var UsersRepository
     * @inject
     */
    public $usersRepository;
    use Nette\SmartObject;

    private $commentRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function getUsers():Nette\Database\Table\Selection
    {
        return $this->usersRepository->getUsers();
    }
}