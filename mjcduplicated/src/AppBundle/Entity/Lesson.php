<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Lesson
 *
 * @ORM\Table(name="lesson")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\LessonRepository")
 */
class Lesson
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
     * @var bool
     *
     * @ORM\Column(name="teacher_isPresent", type="boolean")
     * @Assert\NotBlank()
     */
    private $teacherIsPresent;

    /**
     * @var bool
     *
     * @ORM\Column(name="student_isPresent", type="boolean")
     * @Assert\NotBlank()
     */
    private $studentIsPresent;

    /**
     * @var string
     *
     * @ORM\Column(name="appreciation", type="text", nullable=true)
     */
    private $appreciation;

    /**
    * Many Lessons for One Subscription
    * @ORM\ManyToOne(targetEntity="Subscription", inversedBy="lessons")
    */
    private $subscription;


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
     * @return Lesson
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
     * Set teacherIsPresent
     *
     * @param boolean $teacherIsPresent
     *
     * @return Lesson
     */
    public function setTeacherIsPresent($teacherIsPresent)
    {
        $this->teacherIsPresent = $teacherIsPresent;

        return $this;
    }

    /**
     * Get teacherIsPresent
     *
     * @return bool
     */
    public function getTeacherIsPresent()
    {
        return $this->teacherIsPresent;
    }

    /**
     * Set studentIsPresent
     *
     * @param boolean $studentIsPresent
     *
     * @return Lesson
     */
    public function setStudentIsPresent($studentIsPresent)
    {
        $this->studentIsPresent = $studentIsPresent;

        return $this;
    }

    /**
     * Get studentIsPresent
     *
     * @return bool
     */
    public function getStudentIsPresent()
    {
        return $this->studentIsPresent;
    }

    /**
     * Set appreciation
     *
     * @param string $appreciation
     *
     * @return Lesson
     */
    public function setAppreciation($appreciation)
    {
        $this->appreciation = $appreciation;

        return $this;
    }

    /**
     * Get appreciation
     *
     * @return string
     */
    public function getAppreciation()
    {
        return $this->appreciation;
    }

    /**
     * Set subscription
     *
     * @param \AppBundle\Entity\Subscription $subscription
     *
     * @return Lesson
     */
    public function setSubscription(\AppBundle\Entity\Subscription $subscription = null)
    {
        $this->subscription = $subscription;

        return $this;
    }

    /**
     * Get subscription
     *
     * @return \AppBundle\Entity\Subscription
     */
    public function getSubscription()
    {
        return $this->subscription;
    }
}
