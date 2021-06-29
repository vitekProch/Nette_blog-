<?php


namespace App\Repository;
use Nette;
use PHPStan\Type\StringType;

class UsersRepository extends BaseRepository
{
    public function getUsers()
    {
        return $this->database->table('users');
    }
    public function deleteUsers()
    {
        return $this->database->table('users');
    }
}