<?php


namespace App\Model;
use Nette;
use Nette\Security\IAuthorizator;
use Nette\Security\Permission;
use Nette\Security\IResource;


class My_AuthorizatorFactory
{

    const ROLE_GUEST = 'guest';
    const ROLE_REGISTERED = 'registered';
    const ROLE_EDITOR = 'editor';
    const ROLE_ADMIN = 'admin';

    public function create()
    {
        $acl = new Permission;
        /**
         * Role
         */
        $acl->addRole(self::ROLE_GUEST);
        $acl->addRole(self::ROLE_REGISTERED, self::ROLE_GUEST);
        $acl->addRole(self::ROLE_EDITOR, self::ROLE_REGISTERED);
        $acl->addRole(self::ROLE_ADMIN, self::ROLE_EDITOR);

        /**
         * Resource
         */
        $acl->addResource('article');
        $acl->addResource('comment');
        $acl->addResource('adminPage');
        $acl->addResource('editorPage');

        /**
         * Permission
         */
        $acl->allow(self::ROLE_GUEST, ['article', 'comment'], 'view');

        $acl->allow(self::ROLE_REGISTERED, 'comment', 'add');

        $acl->allow(self::ROLE_EDITOR, ['article','editorPage'], ['accept','delete','edit']);

        $acl->allow(self::ROLE_ADMIN, 'adminPage','delete');

        $acl->allow(self::ROLE_EDITOR, ['editorPage','adminPage'],'view');

        $acl->allow(self::ROLE_ADMIN, $acl::ALL, $acl::ALL);

        return $acl;
    }
}