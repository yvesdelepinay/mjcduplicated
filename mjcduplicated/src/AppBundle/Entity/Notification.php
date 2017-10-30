<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Notification
 *
 * @ORM\Table(name="notification")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NotificationRepository")
 */
class Notification
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
     * Many Notifications for One User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", unique=false)
     */
    private $notifier;

    /**
     * @var string
     *
     * @ORM\Column(name="entityType", type="string", length=64)
     */
    private $entityType;

    /**
     * @var int
     *
     * @ORM\Column(name="id_entityType", type="integer")
     */
    private $idEntityType;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="text")
     */
    private $message;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="specification", type="string", length=64)
     */
    private $specification;

    /**
     * One Notification for Many reading_Notification
     * @ORM\OneToMany(targetEntity="Reading_notification", mappedBy="notification", orphanRemoval=true, cascade={"all"})
     */
     private $reading_notifications;


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
     * Set entityType
     *
     * @param string $entityType
     *
     * @return Notification
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * Get entityType
     *
     * @return string
     */
    public function getEntityType()
    {
        return $this->entityType;
    }

    /**
     * Set idEntityType
     *
     * @param integer $idEntityType
     *
     * @return Notification
     */
    public function setIdEntityType($idEntityType)
    {
        $this->idEntityType = $idEntityType;

        return $this;
    }

    /**
     * Get idEntityType
     *
     * @return int
     */
    public function getIdEntityType()
    {
        return $this->idEntityType;
    }

    /**
     * Set message
     *
     * @param string $message
     *
     * @return Notification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Notification
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set specification
     *
     * @param string $specification
     *
     * @return Notification
     */
    public function setSpecification($specification)
    {
        $this->specification = $specification;

        return $this;
    }

    /**
     * Get specification
     *
     * @return string
     */
    public function getSpecification()
    {
        return $this->specification;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->reading_notifications = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add readingNotification
     *
     * @param \AppBundle\Entity\Reading_notification $readingNotification
     *
     * @return Notification
     */
    public function addReadingNotification(\AppBundle\Entity\Reading_notification $readingNotification)
    {
        $this->reading_notifications[] = $readingNotification;

        return $this;
    }

    /**
     * Remove readingNotification
     *
     * @param \AppBundle\Entity\Reading_notification $readingNotification
     */
    public function removeReadingNotification(\AppBundle\Entity\Reading_notification $readingNotification)
    {
        $this->reading_notifications->removeElement($readingNotification);
    }

    /**
     * Get readingNotifications
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getReadingNotifications()
    {
        return $this->reading_notifications;
    }



    /**
     * Set notifier
     *
     * @param \AppBundle\Entity\User $notifier
     *
     * @return Notification
     */
    public function setNotifier(\AppBundle\Entity\User $notifier = null)
    {
        $this->notifier = $notifier;

        return $this;
    }

    /**
     * Get notifier
     *
     * @return \AppBundle\Entity\User
     */
    public function getNotifier()
    {
        return $this->notifier;
    }
}
