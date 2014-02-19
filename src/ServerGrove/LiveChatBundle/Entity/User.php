<?php

namespace ServerGrove\LiveChatBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

abstract class User
{
    /**
     * @var integer
     * @MongoDB\Id
     */
    private $id;

    /**
     * @var string
     * @MongoDB\String
     * @Assert\NotBlank()
     */
    private $name;

    /**
     * @var string
     * @MongoDB\String
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     * @MongoDB\Date
     */
    private $createdAt;

    /**
     * @var string
     * @MongoDB\Date
     */
    private $updatedAt;

    /**
     * @MongoDB\PrePersist
     */
    public function registerCreatedDate()
    {
        $this->setCreatedAt(date('Y-m-d H:i:s'));
        $this->registerUpdatedDate();
    }

    /**
     * @MongoDB\PreUpdate
     */
    public function registerUpdatedDate()
    {
        $this->setUpdatedAt(date('Y-m-d H:i:s'));
    }

    /**
     * @return Integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return void
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return void
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string $createdAt
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param string $createdAt
     *
     * @return void
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return string $updatedAt
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param string $updatedAt
     *
     * @return void
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    public abstract function getKind();

}