<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Reading_notification
 *
 * @ORM\Table(name="reading_notification")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Reading_notificationRepository")
 */
class Reading_notification
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
     * Many Reads for One User
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="notified_user_id", referencedColumnName="id", unique=false)
     */
    private $notifiedUser;


    /**
    * Many Reading_notifications for One Notification
    * @ORM\ManyToOne(targetEntity="Notification", inversedBy="reading_notifications")
    */
    private $notification;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_read", type="boolean")
     */
    private $isRead;


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
     * Set isRead
     *
     * @param boolean $isRead
     *
     * @return Reading_notification
     */
    public function setIsRead($isRead)
    {
        $this->isRead = $isRead;

        return $this;
    }

    /**
     * Get isRead
     *
     * @return bool
     */
    public function getIsRead()
    {
        return $this->isRead;
    }


    /**
     * Set notifiedUser
     *
     * @param \AppBundle\Entity\User $notifiedUser
     *
     * @return Reading_notification
     */
    public function setnotifiedUser(\AppBundle\Entity\User $notifiedUser = null)
    {
        $this->notifiedUser = $notifiedUser;

        return $this;
    }

    /**
     * Get notifiedUser
     *
     * @return \AppBundle\Entity\User
     */
    public function getnotifiedUser()
    {
        return $this->notifiedUser;
    }

    /**
     * Set notification
     *
     * @param \AppBundle\Entity\Notification $notification
     *
     * @return Reading_notification
     */
    public function setNotification(\AppBundle\Entity\Notification $notification = null)
    {
        $this->notification = $notification;

        return $this;
    }

    /**
     * Get notification
     *
     * @return \AppBundle\Entity\Notification
     */
    public function getNotification()
    {
        return $this->notification;
    }
}
