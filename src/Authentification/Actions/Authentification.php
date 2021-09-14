<?php

namespace App\Authentification\Actions;

use App\Account\Entity\UserEntity;
use App\Account\Table\UserTable;
use Kernel\Database\NoRecodrException;
use Kernel\Session\Session;


class Authentification
{
    private UserTable $userTable;
    private Session $session;
    /**
     * @var UserEntity|null
     */
    private $user;

    public function __construct(UserTable $userTable, Session $session)
    {
        $this->userTable = $userTable;
        $this->session = $session;
    }

    public function login(string $username, string $password): ?UserEntity
    {
        if (empty($username) || empty($password)) {
            return null;
        }

        /** @var UserEntity|null $user */
        $user = $this->userTable->findBy('username', $username);
        if ($user && password_verify($password, $user->password)) {
            $this->session->set('auth.user', $user->getId());
            return $user;
        }
        return null;
    }


    public function getUser(): ?UserEntity
    {
        if ($this->user) {
            return $this->user;
        }
        $userId = $this->session->get('auth.user');
        if ($userId) {
            try {
                $this->user = $this->userTable->find($userId);
                return $this->user;
            } catch (NoRecodrException $exception) {
                $this->session->delete('auth.user');
                return null;
            }
        }
        return null;
    }

    public function logout(): void
    {
        $this->session->delete('auth.user');
    }
    public function setUser(UserEntity $user): void
    {
        $this->session->set('auth.user', $user->id);
        $this->user = $user;
    }
}