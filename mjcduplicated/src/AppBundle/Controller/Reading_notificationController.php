<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Reading_notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Reading_notification controller.
 *
 * @Route("reading_notification")
 * @Security("has_role('ROLE_TEACHER') or has_role('ROLE_STUDENT')")
 */
class Reading_notificationController extends Controller
{
    /**
     * Lists all reading_notification entities.
     *
     * @Route("/", name="reading_notification_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $reading_notifications = $em->getRepository('AppBundle:Reading_notification')->findAll();

        return $this->render('reading_notification/index.html.twig', array(
            'reading_notifications' => $reading_notifications,
        ));
    }

    /**
     * Creates a new reading_notification entity.
     *
     * @Route("/new", name="reading_notification_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $reading_notification = new Reading_notification();
        $form = $this->createForm('AppBundle\Form\Reading_notificationType', $reading_notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reading_notification);
            $em->flush();

            return $this->redirectToRoute('reading_notification_show', array('id' => $reading_notification->getId()));
        }

        return $this->render('reading_notification/new.html.twig', array(
            'reading_notification' => $reading_notification,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a reading_notification entity.
     *
     * @Route("/{id}", name="reading_notification_show")
     * @Method("GET")
     */
    public function showAction(Reading_notification $reading_notification)
    {
        $deleteForm = $this->createDeleteForm($reading_notification);

        return $this->render('reading_notification/show.html.twig', array(
            'reading_notification' => $reading_notification,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing reading_notification entity.
     *
     * @Route("/{id}/edit", name="reading_notification_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Reading_notification $reading_notification)
    {
        $deleteForm = $this->createDeleteForm($reading_notification);
        $editForm = $this->createForm('AppBundle\Form\Reading_notificationType', $reading_notification);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('reading_notification_edit', array('id' => $reading_notification->getId()));
        }

        return $this->render('reading_notification/edit.html.twig', array(
            'reading_notification' => $reading_notification,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a reading_notification entity.
     *
     * @Route("/{id}", name="reading_notification_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Reading_notification $reading_notification)
    {
        $form = $this->createDeleteForm($reading_notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($reading_notification);
            $em->flush();
        }

        return $this->redirectToRoute('reading_notification_index');
    }

    /**
     * Creates a form to delete a reading_notification entity.
     *
     * @param Reading_notification $reading_notification The reading_notification entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Reading_notification $reading_notification)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('reading_notification_delete', array('id' => $reading_notification->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
    /**
           * @Route("/is_read/{id}", name="notification_is_read")
           * @Security("has_role('ROLE_TEACHER') or has_role('ROLE_STUDENT')")

           */
           public function notificationIsReadAction(Request $request, Reading_notification $reading_notification)
           {
               $em = $this->getDoctrine()->getManager();
               $reading_notification->setIsRead(true);
               $em->flush();
            //    dump($reading_notification);
            //    exit;
               return $this->render('default/read.json.twig', [
                   'is_read' => $reading_notification,
               ]
            //    new JsonResponse()
                 );
           }

}
