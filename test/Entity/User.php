<?php

namespace DealerInspire\LaminasTestcase\Test\Entity;

use Doctrine\ORM\Mapping as ORM;
use ZF\OAuth2\Doctrine\Entity\UserInterface;

/**
 * @ORM\Entity
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     */
    protected $password;

    /**
     * @ORM\Column(type="datetime")
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isEmailConfirmed;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $isActivated;

    /**
     * @ORM\Column(type="array")
     */
    protected $roles;

    protected $client;
    protected $accessToken;
    protected $authorizationCode;
    protected $refreshToken;

    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getIsEmailConfirmed()
    {
        return $this->isEmailConfirmed;
    }

    public function getIsActivated()
    {
        return $this->isActivated;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function setIsEmailConfirmed($isEmailConfirmed)
    {
        $this->isEmailConfirmed = $isEmailConfirmed;
    }

    public function setIsActivated($isActivated)
    {
        $this->isActivated = $isActivated;
    }

    public function setRoles($roles)
    {
        $this->roles = $roles;
    }

    public function getArrayCopy()
    {
        return get_object_vars($this);
    }

    public function getClient()
    {
        return $this->client;
    }

    public function getAccessToken()
    {
        return $this->accessToken;
    }

    public function getAuthorizationCode()
    {
        return $this->authorizationCode;
    }

    public function getRefreshToken()
    {
        return $this->refreshToken;
    }
}
