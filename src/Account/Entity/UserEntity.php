<?php
namespace App\Account\Entity;

class UserEntity
{
    public  $id;
    public $username;
    public $email;
    public $password;
    public $role;
    private $name;
    private $lastName;
    private $due;
    private $whoInvite;


    public function getName(): string
    {
        return $this->name;
    }


    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }


    public function getLastName(): string
    {
        return $this->lastName;
    }


    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;
        return  $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getRole(): array
    {
        return [$this->role];
    }

    public function getId(): int
    {
        return $this->id;
    }


    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;
        return  $this;
    }

    public function setDue(?int $due): self
    {
        $this->due = $due;
        return $this;
    }


    public function getDue(): ?int
    {
        return $this->due;
    }

    /**
     * @return string
     */
    public function getWhoInvite(): string
    {
        return $this->whoInvite;
    }

    /**
     * @param string|null $whoInvite
     * @return UserEntity
     */
    public function setWhoInvite(?string $whoInvite): UserEntity
    {
        $this->whoInvite = $whoInvite;
        return $this;
    }
}