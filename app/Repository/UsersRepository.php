<?php


namespace App\Repository;
use Nette;
class UsersRepository extends BaseRepository
{
    public const TABLE_NAME_USERS = 'users';


    public function getUsers():Nette\Database\Table\Selection
    {
        return $this->database->table(self::TABLE_NAME_USERS);
    }
}