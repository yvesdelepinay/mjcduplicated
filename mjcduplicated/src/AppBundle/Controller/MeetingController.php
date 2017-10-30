<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Meeting;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Meeting controller.
 *
 * @Route("meeting")
 */
class MeetingController extends Controller
{
    /**
     * Lists all meeting entities.
     *
     * @Route("/", name="meeting_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $meetings = $em->getRepository('AppBundle:Meeting')->findAll();

        return $this->render('meeting/index.html.twig', array(
            'meetings' => $meetings,
        ));
    }

    /**
     * Creates a new meeting entity.
     *
     * @Route("/new", name="meeting_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $meeting = new Meeting();
        $form = $this->createForm('AppBundle\Form\MeetingType', $meeting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($meeting);
            $em->flush();

            return $this->redirectToRoute('meeting_show', array('id' => $meeting->getId()));
        }

        return $this->render('meeting/new.html.twig', array(
            'meeting' => $meeting,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a meeting entity.
     *
     * @Route("/{id}", name="meeting_show")
     * @Method("GET")
     */
    public function showAction(Meeting $meeting)
    {
        $deleteForm = $this->createDeleteForm($meeting);

        return $this->render('meeting/show.html.twig', array(
            'meeting' => $meeting,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing meeting entity.
     *
     * @Route("/{id}/edit", name="meeting_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Meeting $meeting)
    {
        $deleteForm = $this->createDeleteForm($meeting);
        $editForm = $this->createForm('AppBundle\Form\MeetingType', $meeting);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('meeting_edit', array('id' => $meeting->getId()));
        }

        return $this->render('meeting/edit.html.twig', array(
            'meeting' => $meeting,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a meeting entity.
     *
     * @Route("/{id}", name="meeting_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Meeting $meeting)
    {
        $form = $this->createDeleteForm($meeting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($meeting);
            $em->flush();
        }

        return $this->redirectToRoute('meeting_index');
    }

    /**
     * Creates a form to delete a meeting entity.
     *
     * @param Meeting $meeting The meeting entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Meeting $meeting)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('meeting_delete', array('id' => $meeting->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
