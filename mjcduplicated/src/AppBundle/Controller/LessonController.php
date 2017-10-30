<?php

namespace AppBundle\Controller;
use AppBundle\Entity\Notification;
use AppBundle\Entity\Reading_notification;
use AppBundle\Entity\Lesson;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Lesson controller.
 *
 * @Route("lesson")
 * @Security("has_role('ROLE_ADMIN')")
 */
class LessonController extends Controller
{
    /**
     * Lists all lesson entities.
     *
     * @Route("/", name="lesson_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $lessons = $em->getRepository('AppBundle:Lesson')->findAll();

        return $this->render('lesson/index.html.twig', array(
            'lessons' => $lessons,
        ));
    }

    /**
     * Creates a new lesson entity.
     *
     * @Route("/new", name="lesson_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $lesson = new Lesson();
        $form = $this->createForm('AppBundle\Form\LessonType', $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $lesson->setAppreciation(null);
            $em->persist($lesson);
            $em->flush();

            return $this->redirectToRoute('lesson_show', array('id' => $lesson->getId()));
        }

        return $this->render('lesson/new.html.twig', array(
            'lesson' => $lesson,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a lesson entity.
     *
     * @Route("/{id}", name="lesson_show")
     * @Method("GET")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_TEACHER') or has_role('ROLE_STUDENT')")
     */
    public function showAction(Lesson $lesson)
    {
        $deleteForm = $this->createDeleteForm($lesson);

        return $this->render('lesson/show.html.twig', array(
            'lesson' => $lesson,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing lesson entity.
     *
     * @Route("/{id}/edit", name="lesson_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Lesson $lesson)
    {
        $deleteForm = $this->createDeleteForm($lesson);
        $editForm = $this->createForm('AppBundle\Form\LessonType', $lesson);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('lesson_edit', array('id' => $lesson->getId()));
        }

        return $this->render('lesson/edit.html.twig', array(
            'lesson' => $lesson,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Update the presence of one teacher one student for one lesson.
     *
     * @Route("/{id}/presence/edit", name="lesson_presence_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TEACHER') or has_role('ROLE_STUDENT')")
     */
    public function presenceEditAction(Request $request, Lesson $lesson)
    {
        /**
        * Instanciation de mes variables
        */

        // Pour notification, il me faut : userId , entityType, entityTypeId, message, createdAt et specification
        $instigator=$this->getUser();
        $userId = $this->getUser()->getId();
        $entityType = "Lesson";
        // Je crée une notification d'absence
        $lessonId = $lesson->getId();
        // dump($lessonDate);
        // exit;
        $entityTypeId = $lessonId;
        $specification = 'absence';

        //Je récupère la requete et ses attributs
        $message = "";
        $typeUser = $request->get('type_user');
        $presence = $request->get('presence');
        $presenceBoolean = $presence === 'true' ? true : false;


        // Je récupère la subscription pour les Users
        $teacher = $lesson->getSubscription()->getTeacher();
        $student = $lesson->getSubscription()->getStudent();
        $teacherId = $lesson->getSubscription()->getTeacher()->getId();
        $studentId = $lesson->getSubscription()->getStudent()->getId();

      $requests =  $request->request->all();
        // dump($requests);
        // exit;


        // Je défini l'user et la personne notifiée en fonction du rôle
        if ( $typeUser == "ROLE_TEACHER") {
            $user = $teacher;
            $notifiedUser = $student;
        } elseif ( $typeUser == "ROLE_STUDENT") {
            $user = $student;
            $notifiedUser = $teacher;
        }


        $em = $this->getDoctrine()->getManager();

        // Test pour voir si une notif existe déjà
        $oldNotification =  $em->getRepository('AppBundle:Notification')->findNotificationByEntityTypeIdAndSpecification($entityType,$entityTypeId, $specification, $userId);
        // dump($oldNotification);
        // exit;
       // Si une notif existe déjà
       if ($oldNotification == true) {
            // Je récupère l'id de la notif
            $notificationId = $oldNotification[0]->getId();
            // Je récupère la lecture liée à la notif
            $oldReadingNotif = $em->getRepository('AppBundle:Reading_notification')->findReadLinkNotif($notificationId, $notifiedUser);

            // Je change la date et le statut de lecture
            $oldReadingNotif[0]->setIsRead(false);
            $oldNotification[0]->setCreatedAt(new \DateTime);

           $messageExist="ça existe";
        } else {
           // Autrement, je crée une nouvelle notification
        //    $messageExist= "ça n'existe pas";
        //    dump($messageExist);

           $notification = new Notification();
           $notification->setNotifier($instigator);
           $notification->setEntityType($entityType);
           // Je prends l'id de la Lesson
           $notification->setIdEntityType($lessonId);
           // Message
           // $notification->setMessage($messageNotif);
           // Date de création de la notif : current date
           $notification->setCreatedAt(new \DateTime());
           // Je note le type de la notif, ici lesson
           $notification->setSpecification($specification);
           $readingNotification = new Reading_notification();
           $readingNotification->setIsRead(false);
           $readingNotification->setNotification($notification);
           $readingNotification->setnotifiedUser($notifiedUser);

           // Je rajoute le reading_notification
           $notification->addReadingNotification($readingNotification);
       }

        //Si la requête concerne le teacher
        if ($typeUser == "ROLE_TEACHER") {
            // Je crée le message de notification en fonction de la présence du prof
            if ($presenceBoolean == false) {
                $messageNotif = "Votre professeur " . $teacher->getFirstname(). " "  . $teacher->getLastName() . " sera absent";
             //    dump($messageNotif);
             //    exit;
            } else {
                $messageNotif = $messageNotif = "Votre professeur " . $teacher->getFirstname(). " "  . $teacher->getLastName() . " sera présent";
            }
            // Je modifie le teacherIsPresent dans Lesson
            $lesson->setTeacherIsPresent($presenceBoolean);

        }
        elseif ($typeUser == "ROLE_STUDENT") {
            // Je crée le message de notification en fonction de la présence du prof
            if ($presenceBoolean == false) {
                $messageNotif = "Votre élève " . $student->getFirstname(). " "  . $student->getLastName() . " sera absent";

            } else {
                $messageNotif = $messageNotif = "Votre élève " . $student->getFirstname(). " "  . $student->getLastName() . " sera présent";
            }
            // Je modifie le setStudentIsPresent dans Lesson
            $lesson->setStudentIsPresent($presenceBoolean);
        }
        else {
            $message ="Erreur lors de la modification";
 }
         if ($oldNotification == true) {
             $oldNotification[0]->setMessage($messageNotif);
         } else {
             $notification->setMessage($messageNotif);
             $em->persist($notification);
             $em->persist($readingNotification);

         }

        // J'enregistre en base de donnée
        $em->flush();
        return $this->render('default/presence.html.twig', [
            'message' => 'modification effectuée',
            'lesson' => $lesson,
        ]);
    }


    /**
     * Update an observation for one lesson.
     *
     * @Route("/{id}/observation/edit", name="lesson_observation_edit")
     * @Method({"GET", "POST"})
     * @Security("has_role('ROLE_TEACHER')")
     */
    public function observationEditAction(Request $request, Lesson $lesson)
    {
        //Je récupère la requete et ses attributs
        $observation = $request->get('appreciation');
    //    $clearObservation = strip_tags($observation);
    // $clearObservation = nl2br($observation);
    //   $clearObservation =
        $em = $this->getDoctrine()->getManager();

        //Je modifie l'observation
        $lesson->setAppreciation($observation);
        // J'enregistre dans la base
        $em->persist($lesson);
        $em->flush();


        return $this->render('default/presence.html.twig', [
        'message' => 'modification de l\'observation effectuée',
        'lesson' => $lesson
        ]);
}

    /**
     * Deletes a lesson entity.
     *
     * @Route("/{id}", name="lesson_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Lesson $lesson)
    {
        $form = $this->createDeleteForm($lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($lesson);
            $em->flush();
        }

        return $this->redirectToRoute('lesson_index');
    }

    /**
     * Creates a form to delete a lesson entity.
     *
     * @param Lesson $lesson The lesson entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Lesson $lesson)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('lesson_delete', array('id' => $lesson->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


}
