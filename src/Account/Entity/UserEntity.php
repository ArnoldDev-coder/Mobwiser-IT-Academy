<?php
namespace App\Account\Entity;

class UserEntity
{
    public  $id;
    public $username;
    public $email;
    public $password;
    public $roles;
    private $firstName;


    public function getFirstName(): string
    {
        return $this->firstName;
    }


    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }


    public function getLastName(): string
    {
        return $this->lastName;
    }


    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return  $this;
    }
    private $lastName;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getRoles(): array
    {
        return [$this->roles];
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;
        return  $this;
    }
}