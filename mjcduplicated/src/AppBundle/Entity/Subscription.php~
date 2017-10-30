<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Subscription
 *
 * @ORM\Table(name="subscription")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubscriptionRepository")
 */
class Subscription
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
     * @var \DateTime
     *
     * @ORM\Column(name="startAt", type="datetime")
     */
    private $startAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finishAt", type="datetime")
     */
    private $finishAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="duration", type="integer", nullable=true)
     */
    private $duration;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="subscriptionAt", type="datetime")
     */
    private $subscriptionAt;

    /**
     * One Subscription has One Teacher(in User with ROLE_TEACHER)
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="teacher_id", referencedColumnName="id", unique=false)
     */
    private $teacher;

    /**
     * One Subscription has One Student(in User with ROLE_STUDENT)
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="student_id", referencedColumnName="id", unique=false)
     */
    private $student;

    /**
     * One Subscription for Many Lessons
     * @ORM\OneToMany(targetEntity="Lesson", mappedBy="subscription", orphanRemoval=true, cascade={"all"})
     */
     private $lessons;

    /**
     * @ORM\ManyToOne(targetEntity="Specialty", cascade={"persist"})
     *
     */
    private $specialties;

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
     * Set startAt
     *
     * @param \DateTime $startAt
     *
     * @return Subscription
     */
    public function setStartAt($startAt)
    {
        $this->startAt = $startAt;

        return $this;
    }

    /**
     * Get startAt
     *
     * @return \DateTime
     */
    public function getStartAt()
    {
        return $this->startAt;
    }

    /**
     * Set finishAt
     *
     * @param \DateTime $finishAt
     *
     * @return Subscription
     */
    public function setFinishAt($finishAt)
    {
        $this->finishAt = $finishAt;

        return $this;
    }

    /**
     * Get finishAt
     *
     * @return \DateTime
     */
    public function getFinishAt()
    {
        return $this->finishAt;
    }

    /**
     * Set subscriptionAt
     *
     * @param \DateTime $subscriptionAt
     *
     * @return Subscription
     */
    public function setSubscriptionAt($subscriptionAt)
    {
        $this->subscriptionAt = $subscriptionAt;

        return $this;
    }

    /**
     * Get subscriptionAt
     *
     * @return \DateTime
     */
    public function getSubscriptionAt()
    {
        return $this->subscriptionAt;
    }

    /**
     * Set teacher
     *
     * @param \AppBundle\Entity\Subscription $teacher
     *
     * @return Subscription
     */
    public function setTeacher(\AppBundle\Entity\User $teacher = null)
    {
        $this->teacher = $teacher;

        return $this;
    }

    /**
     * Get teacher
     *
     * @return \AppBundle\Entity\Subscription
     */
    public function getTeacher()
    {
        return $this->teacher;
    }

    /**
     * Set student
     *
     * @param \AppBundle\Entity\Subscription $student
     *
     * @return Subscription
     */
    public function setStudent(\AppBundle\Entity\User $student = null)
    {
        $this->student = $student;

        return $this;
    }

    /**
     * Get student
     *
     * @return \AppBundle\Entity\Subscription
     */
    public function getStudent()
    {
        return $this->student;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->lessons = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add lesson
     *
     * @param \AppBundle\Entity\Lesson $lesson
     *
     * @return Subscription
     */
    public function addLesson(\AppBundle\Entity\Lesson $lesson)
    {
        $this->lessons[] = $lesson;

        return $this;
    }

    /**
     * Remove lesson
     *
     * @param \AppBundle\Entity\Lesson $lesson
     */
    public function removeLesson(\AppBundle\Entity\Lesson $lesson)
    {
        $this->lessons->removeElement($lesson);
    }

    /**
     * Get lessons
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getLessons()
    {
        return $this->lessons;
    }


    /**
     * Set specialties
     *
     * @param \AppBundle\Entity\Specialty $specialties
     *
     * @return Subscription
     */
    public function setSpecialties(\AppBundle\Entity\Specialty $specialties = null)
    {
        $this->specialties = $specialties;

        return $this;
    }

    /**
     * Get specialties
     *
     * @return \AppBundle\Entity\Specialty
     */
    public function getSpecialties()
    {
        return $this->specialties;
    }

    /**
     * Set duration
     *
     * @param \DateTime $duration
     *
     * @return Subscription
     */
    public function setDuration($duration)
    {
        $this->duration = $duration;

        return $this;
    }

    /**
     * Get duration
     *
     * @return \DateTime
     */
    public function getDuration()
    {
        return $this->duration;
    }
}
