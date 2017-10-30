<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Specialty
 *
 * @ORM\Table(name="specialty")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SpecialtyRepository")
 */
class Specialty
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $name;





    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Specialty
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->subsciptions = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add subsciption
     *
     * @param \AppBundle\Entity\Subscription $subsciption
     *
     * @return Specialty
     */
    public function addSubsciption(\AppBundle\Entity\Subscription $subsciption)
    {
        $this->subsciptions[] = $subsciption;

        return $this;
    }


    /**
     * Add subscription
     *
     * @param \AppBundle\Entity\Subscription $subscription
     *
     * @return Specialty
     */
    public function addSubscription(\AppBundle\Entity\Subscription $subscription)
    {
        $this->subscriptions[] = $subscription;

        return $this;
    }
        public function __toString()
        {
            return $this->name;
        }

}
