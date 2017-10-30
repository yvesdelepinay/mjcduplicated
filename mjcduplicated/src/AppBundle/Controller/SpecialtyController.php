<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Specialty;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Specialty controller.
 *
 * @Route("specialty")
 * @Security("has_role('ROLE_ADMIN')")
 */
class SpecialtyController extends Controller
{
    /**
     * Lists all specialty entities.
     *
     * @Route("/", name="specialty_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $specialties = $em->getRepository('AppBundle:Specialty')->findAll();

        return $this->render('specialty/index.html.twig', array(
            'specialties' => $specialties,
        ));
    }

    /**
     * Creates a new specialty entity.
     *
     * @Route("/new", name="specialty_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $specialty = new Specialty();
        $form = $this->createForm('AppBundle\Form\SpecialtyType', $specialty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($specialty);
            $em->flush();

            return $this->redirectToRoute('specialty_show', array('id' => $specialty->getId()));
        }

        return $this->render('specialty/new.html.twig', array(
            'specialty' => $specialty,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a specialty entity.
     *
     * @Route("/{id}", name="specialty_show")
     * @Method("GET")
     */
    public function showAction(Specialty $specialty)
    {
        $deleteForm = $this->createDeleteForm($specialty);

        return $this->render('specialty/show.html.twig', array(
            'specialty' => $specialty,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing specialty entity.
     *
     * @Route("/{id}/edit", name="specialty_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Specialty $specialty)
    {
        $deleteForm = $this->createDeleteForm($specialty);
        $editForm = $this->createForm('AppBundle\Form\SpecialtyType', $specialty);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('specialty_edit', array('id' => $specialty->getId()));
        }

        return $this->render('specialty/edit.html.twig', array(
            'specialty' => $specialty,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a specialty entity.
     *
     * @Route("/{id}", name="specialty_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Specialty $specialty)
    {
        $form = $this->createDeleteForm($specialty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($specialty);
            $em->flush();
        }

        return $this->redirectToRoute('specialty_index');
    }

    /**
     * Creates a form to delete a specialty entity.
     *
     * @param Specialty $specialty The specialty entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Specialty $specialty)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('specialty_delete', array('id' => $specialty->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
