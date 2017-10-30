<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Subscription;
use AppBundle\Entity\User;
use AppBundle\Entity\Lesson;
// use AppBundle\Controller\FerieController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * Subscription controller.
 *
 * @Route("subscription")
 * @Security("has_role('ROLE_ADMIN')")
 */
class SubscriptionController extends Controller
{
    /**
     * Lists all subscription entities.
     *
     * @Route("/", name="subscription_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $subscriptions = $em->getRepository('AppBundle:Subscription')->findAll();
        //dump($subscriptions);
        return $this->render('subscription/index.html.twig', array(
            'subscriptions' => $subscriptions,
        ));
    }

    /**
     * Creates a new subscription entity.
     *
     * @Route("/new", name="subscription_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $subscription = new Subscription();
        $form = $this->createForm('AppBundle\Form\SubscriptionType', $subscription);
        $form->handleRequest($request);
        //dump($subscription);

        // current date
        $subscription->setSubscriptionAt(new \DateTime());

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            /**
             * Enregistrement de la date de fin en fonction de la date de début et la durée d'un cours
             */
            // Je récupère la date du 1er cours
            $startAt = $subscription->getStartAt();
            // dump($startAt);
            // Je récupère la durée d'un cours
            $duration = $subscription->getDuration();
            // Je fais mon calcul pour ma date de fin (ici duration est enregistrée en int avec le nombre de secondes)
            $durationforDate = 'PT0H'.$duration .'S';
            // $durationModyfi
            // dump($durationforDate);
            // exit;

            //'PT0H1800S'=30min
            // L'objet Datetime a dû être passé "avec"
            // Il faut donc recréer un objet nouveau à partir d'une chaine date...
            // dump($startAt->format('Y-m-d H:i:s'));
            $startDate = new \Datetime($startAt->format('Y-m-d H:i:s'));
            $subscription->setFinishAt($startDate);
            $finishAt = $subscription->getFinishAt();
            $finishAt->add(new \DateInterval($durationforDate));
            // dump($startAt);
            // dump($finishAt);
            // exit;

            // $startAt->modify('')
            // Je mets à jour ma date de fin avec set
            $subscription->setFinishAt($finishAt);
            $appreciation = '';
            /**
             * Enregistrer automatiquement les leçons en fonction d'une inscription
             */
             // La première leçon aura la même startAt que l'inscription
             $lesson = new Lesson();
             $lesson->setStartAt($startDate);
             // Même si je mets le setter de l'appréciation à null, ça affiche null dans le textarea
            //  $lesson->setAppreciation(null);
             $lesson->setTeacherIsPresent(true);
             $lesson->setStudentIsPresent(true);
             $lesson->setSubscription($subscription);
             // Je lie la lesson à la subscription
             $subscription->addLesson($lesson);

             // Pour enregistrer les autres leçons, il me faut définir une date de début des cours et une date de fin

             $format = 'Y-m-d';
             $beginingDate = \DateTime::createFromFormat($format, '2017-09-10');

            //  dump($beginingDate);

             $format = 'Y-m-d';
             $holidayDate = \DateTime::createFromFormat($format, '2018-07-10');
             // Et j'enregistre l'inscription
             $em->persist($subscription);
             $em->flush();


             //Je crée une leçon toute les semaines à la même heure si la $date est > $beginingDate  et < $holidayDate

             //Je récupère la startAt
            $date = $lesson->getStartAt();
            // dump($date);
            $newDate = '';
            // Tant que la date est + petite que la date des vacances



            while ($date <= $holidayDate) {
                //J'ajoute 7 jours à ma date
                $date->modify('+7 day');
            //    dump($date);

                //Je mets la nouvelle date en timestamp pour vérifier qu'elle n'est pas un jour férié et pas pendant les vacances.
                $timestampDate = $date->getTimestamp();
                if ($date < $holidayDate) {
                //dump($timestampDate);
                if (FerieController::estFerie($timestampDate))
                    {
                        $date->modify('+7 day');
                        if (FerieController::estFerie($timestampDate))
                            {
                                $date->modify('+7 day');
                            }
                    }
}

$timestampDate = $date->getTimestamp();

if ($date < $holidayDate) {


                // Si la date n'est pas égale à un jour férié

                // Et non comprise pendant les vacances scolaires (à enregistrer dès le début)

                // Je crée donc une nouvelle leçon avec cette date;
                $lesson = new Lesson();
                $lesson->setStartAt($date);
                dump($date);

                $lesson->setTeacherIsPresent(true);
                $lesson->setStudentIsPresent(true);
                // Mettre null au lieu de $appreciation si l'on veut que ça marque null
                // $lesson->setAppreciation($appreciation);
                $lesson->setSubscription($subscription);
                // Je lie la lesson à la subscription
                $subscription->addLesson($lesson);
                // $newDate .= $date->format('Y-m-d');
                // Et j'enregistre l'inscription
                $em->persist($subscription);
                // Si la nouvelle date de leçon < date des vacances, on enregistre
                if ($date<$holidayDate) {
                    $em->flush();
                }
}
            }
    // exit;

            //  dump($holidayDate);
            //  exit;



            return $this->redirectToRoute('subscription_show', array('id' => $subscription->getId()));
        }

        return $this->render('subscription/new.html.twig', array(
            'subscription' => $subscription,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a subscription entity.
     *
     * @Route("/{id}", name="subscription_show")
     * @Method("GET")
     */
    public function showAction(Subscription $subscription)
    {
        $deleteForm = $this->createDeleteForm($subscription);

        return $this->render('subscription/show.html.twig', array(
            'subscription' => $subscription,
            'delete_form' => $deleteForm->createView(),
        ));
    }




    /**
     * Displays a form to edit an existing subscription entity.
     *
     * @Route("/{id}/edit", name="subscription_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Subscription $subscription)
    {
        $deleteForm = $this->createDeleteForm($subscription);
        $editForm = $this->createForm('AppBundle\Form\SubscriptionType', $subscription);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('subscription_edit', array('id' => $subscription->getId()));
        }

        return $this->render('subscription/edit.html.twig', array(
            'subscription' => $subscription,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a subscription entity.
     *
     * @Route("/{id}", name="subscription_delete", requirements={"id": "\d+"})
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Subscription $subscription)
    {
        $form = $this->createDeleteForm($subscription);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($subscription);
            $em->flush();
        }

        return $this->redirectToRoute('subscription_index');
    }

    /**
     * Creates a form to delete a subscription entity.
     *
     * @param Subscription $subscription The subscription entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Subscription $subscription)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('subscription_delete', array('id' => $subscription->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
