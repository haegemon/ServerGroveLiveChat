<?php

namespace ServerGrove\LiveChatBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Description of Operator
 *
 * @author Ismael Ambrosi<ismael@servergrove.com>
 * @MongoDB\Document(
 * collection="operator",
 * repositoryClass="ServerGrove\LiveChatBundle\Document\OperatorRepository"
 * )
 * @MongoDB\InheritanceType("SINGLE_COLLECTION")
 */
class Operator extends User implements UserInterface
{

    /**
     * @var boolean
     * @MongoDB\Field(type="boolean")
     */
    private $isOnline;

    /**
     * @var boolean
     * @MongoDB\Field(type="boolean")
     */
    private $isActive = true;

    /**
     * @var array
     * @MongoDB\Collection()
     */
    private $roles;

    /**
     * @var string
     * @MongoDB\String
     * @Assert\NotBlank()
     */
    private $passwd;

    /**
     * @MongoDB\ReferenceMany(targetDocument="ServerGrove\LiveChatBundle\Document\Operator\Rating")
     */
    private $ratings;

    /**
     * @MongoDB\ReferenceMany(targetDocument="OperatorDepartment", inversedBy="operators")
     */
    private $departments;

    /** @MongoDB\String */
    private $salt;

    public function __construct()
    {
        $this->salt             = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->departments      = new ArrayCollection();
        $this->ratings          = new ArrayCollection();
        $this->roles            = array('ROLE_OPERATOR');
    }

    public function addRating(Operator\Rating $rating)
    {
        $this->ratings->add($rating);
    }

    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * @return boolean $isOnline
     */
    public function getIsOnline()
    {
        return $this->isOnline;
    }

    /**
     * @param boolean $isOnline
     *
     * @return void
     */
    public function setIsOnline($isOnline)
    {
        $this->isOnline = $isOnline;
    }

    /**
     * @return boolean $isActive
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param boolean $isActive
     *
     * @return void
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return string $passwd
     */
    public function getPasswd()
    {
        return $this->passwd;
    }

    /**
     * @param string $passwd
     *
     * @return void
     */
    public function setPasswd($passwd)
    {
        $this->passwd = $passwd;
    }

    /**
     * @return \Doctrine\Common\Collections\ArrayCollection
     */
    public function getDepartments()
    {
        return $this->departments;
    }

    public function addDepartment(OperatorDepartment $department)
    {
        $this->departments->add($department);
    }

    public function setDepartments($departments)
    {
        $this->departments = $departments;
    }

    public function isAdministrator()
    {
        return in_array('ROLE_ADMIN', $this->roles);
    }

    public function promote()
    {
        $this->roles = array('ROLE_ADMIN', 'ROLE_USER');
    }

    public function getKind()
    {
        return 'Operator';
    }

    # -- AccountInterface implementation ----------------

    /**
     * @return string
     */
    public function __toString()
    {
        return strtr('(:id) :name, :email', array(
            ':email' => $this->getEmail(),
            ':name'  => $this->getName(),
            ':id'    => $this->getId()
        ));
    }

    /**
     * @param AccountInterface $account
     *
     * @return boolean
     */
    public function equals(UserInterface $account)
    {
        return $account instanceof Operator
            && $account->getId() == $this->getId()
            && $account->getUsername() == $this->getUsername();
    }

    public function eraseCredentials()
    {

    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return $this->getPasswd();
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        return $this->roles;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->getEmail();
    }
}