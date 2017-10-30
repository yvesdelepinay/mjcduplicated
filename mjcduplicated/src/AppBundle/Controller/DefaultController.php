<?php

namespace AppBundle\Controller;
use Symfony\Component\HttpFoundation\ParameterBag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use AppBundle\Entity\Subscription;
use AppBundle\Entity\Lesson;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\HttpFoundation\RequestStack;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @Security("has_role('ROLE_ADMIN') or has_role('ROLE_TEACHER') or has_role('ROLE_STUDENT')")
     */
    public function indexAction(Request $request)
    {
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
         ]);
    }

    /**
    * Pour prof ou eleve, montre toutes les leçons qu'il a pour une date donnée
    * @Route("planning/{date}", name="planning_date")
    * @Security("has_role('ROLE_TEACHER') or has_role('ROLE_STUDENT')")
    */
    public function userDateAction(Request $request)
    {
        $dateRequest = $request->get('date');
        $id = $this->getUser()->getId();

        $date = new \DateTime($dateRequest);
        $em = $this->getDoctrine()->getManager();
        $lessons = $em->getRepository('AppBundle:Lesson')->getLessonsFromDateAndId($date, $id);
        // Tu peux tapper les enter sur ta textaera. ensuite tu stockes dans ta base avec un htmlentities[$tontexte) et tu réaffiches tout dans un style tres pur avec un html_entity_decode($tontexte);
        // $esscape = htmlentities($lessons);
        // dump($esscape);
        // exit;
        return $this->render('default/planning.json.twig', [
            'lessons' => $lessons,
        ],
        new JsonResponse()
          );
    }

    /**
    * Pour l'administrateur, montre toutes les lessons d'une journée
    * @Route("/date/{date}", name="date")
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function dateAction(Request $request)
    {
        $dateRequest = $request->get('date');

        $date = new \DateTime($dateRequest);
        $em = $this->getDoctrine()->getManager();
        $lessons = $em->getRepository('AppBundle:Lesson')->getLessonsFromDate($date);

        return $this->render('default/date.json.twig', [
            'lessons' => $lessons,
        ],
        new JsonResponse()
          );
    }

    /**
     * Finds and displays informations about notification entity.
     *
     * @Route("notification/infos/{entity}/{id}", name="notification_infos")
     *
     */
    public function notificationInfosAction(Request $request)
    {
        $entity = $request->get('entity');
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('AppBundle:' .  $entity)->findOneById($id);
        // dump($result);
        // exit;
        return $this->render('default/infosnotif.json.twig', [
            'activity' => $result,
        ],
        new JsonResponse()
          );
    }


    /**
    * @Route("/next", name="next")
    * @Security("has_role('ROLE_TEACHER') or has_role('ROLE_STUDENT')")
    */
    public function nextAction()
    {
       $em = $this->getDoctrine()->getManager();
       $id = $this->getUser()->getId();
       $result = $em->getRepository('AppBundle:Lesson')->lessonsNowAfter($id);

       return $this->render('default/next.json.twig', [
           'result' => $result,
       ],
       new JsonResponse()
         );
    }

    /**
    * @Route("notifications", name="notifications")
    * @Security("has_role('ROLE_TEACHER') or has_role('ROLE_STUDENT')")
    **/
    public function notificationsAction(){
        $em = $this->getDoctrine()->getManager();
        $id = $this->getUser()->getId();
        $result = $em->getRepository('AppBundle:Notification')->findAllNotificationsForOneUser($id);
        // dump($result);
        // echo "Resultats :";
        // exit;
    //     for ($ligne=0 ; $ligne < ; $ligne++) {
    //     $n = $ligne +1
    //     echo "Enregistrement N°". $n ."<br>"
    // }
    // for ($col=0; $col < ; $col++) {
    //     echo $result[$ligne][$col];
    // }
// Tester ajout clé tableau multidimensionnel
// $test= array('results'=>array(array('a'=>'','b'=>'', 'c'=>''),array('a'=>'','b'=>'','c'=>'')));
// var_dump($test);
// foreach($test as $key => $value){
//     $test = array_merge($test, $key], array('d' => 'val'));
// }
// var_dump($test);

// exit;
    // Pour chaque résultat
    // dump($result);
// foreach ($result as $number =>$key) {
//     dump($key);
// $push = array_push($key, "apple", "raspberry");
// dump($push);
// $number['laLessonDate'] = "date de la leçon : ";
    // echo $number['entityType'];
    // Je check si il y a des leçons et j'affiche l'id de la leçon
    // foreach ($key as $subkey) {
    //     //J'ajoute une clé à mes objets
    //     $bla['datedelObjetnotif'] = 'date inconnue';
    //     $resutlatFinalkey = array_merge($key, $bla);
    //
    //     if ($subkey==='Lesson') {
    //         echo "C'est une leçon";
    //     // dump($subkey);
    //     $idLesson = $key['idEntityType'];
    //     dump($idLesson);
        // Je fais la requête pour trouver la date de la lesson
        // dump($key['idEntityType']);

        //J'ajoute la date à ma nouvelle clé
        // Un truc dans le genre
        // $test['LessonDate'] = 'DateTime Récupéré';
        // // $resutlatFinal = array_merge($key, $test);
        //
        // $resutlatFinalkey = array_merge($key, $test);
        //     dump($resutlatFinalkey);
        //     $resutlatFinalResult = array_merge($result, $test);
        //     dump($resutlatFinal);
    // }
    //  dump($resutlatFinalkey);
    //  dump($resultatFinalKey['datedelObjetnotif']);


    // }
    //  dump($resutlatFinalkey);
// }

    // dump($result);
    // // dump($resutlatFinal);
    //     exit;

        return $this->render('default/notifications.json.twig', [
            'result' => $result,
            // 'type' => $type,
        ],
        new JsonResponse()
          );
    }


    /**
     * @Route("/activity/{id}", name="activity")
     * @Security("has_role('ROLE_TEACHER') or has_role('ROLE_STUDENT')")
     */
    public function showActivityAction(Request $request, Lesson $lesson)
    {
       return $this->redirectToRoute('homepage');
    }

    /**
     * Finds and displays a subscription entity.
     *
     * @Route("/test", name="test")
     * @Method("GET")
     */
    public function testAction()
    {
        $em = $this->getDoctrine()->getManager();
        $subscriptions = $em->getRepository('AppBundle:Subscription')->showAllAction();

        return $this->render('default/test.html.twig', [
            'inscriptions' => $subscriptions,
        ]);
    }

    /**
     * @Route("/test/lesson", name="test_lesson")
     * @Method("GET")
     */
    public function LessonAction()
    {
        $em = $this->getDoctrine()->getManager();
        $lessons = $em->getRepository('AppBundle:Lesson')->showAllAction();
        return $this->render('default/lesson.json.twig', [
            'lessons' => $lessons,
        ]);
    }

        /**
     * @Route("/ajax", name="ajax")
     *
     */
    public function ajaxAction(Request $request)
    {
        $userId = $this->getUser()->getId();
        // dump($userId);
        // exit;
        $em = $this->getDoctrine()->getManager();
        $lessons = $em->getRepository('AppBundle:Lesson')->showAllAction();

        $date = $request->request->get('date');

        return $this->render('admin/today.json.twig', [
            'lessons' => $lessons,
            'date'=> $date,
        ],
        new JsonResponse()
                );
    }

    /**
    * @Route("/ajax/date/{id}", name="ajax_Date")
    *
    */
    public function ajaxDateAction(Request $request)
    {
        if ($request->isXMLHttpRequest()) {
            // $id = $request->get('id');
            $teacherId = $request->get('teacher_id');
            // Faire une fonction pour récupérer tous les cours de l'user en fonction de la date envoyée en ajax
            $lessons =  getRepository('AppBundle:Subscription')->showLessonsByTeacherId($teacherId);
            return new JsonResponse($lessons);
        }
    }

    /**
     *
     * @Route("/json/subscriptions", name="json_get_subscriptions")
     * @Method("GET")
     */
    public function jsonGetSubscriptionsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $subscriptions = $em->getRepository('AppBundle:Subscription')->showAllAction();
        return $this->render('default/test.json.twig', [
            'inscriptions' => $subscriptions,
        ],
        new JsonResponse()
    );
    }

    /**
     * @Route("/show/myStudents", name="show_myStudents")
     * @Security("has_role('ROLE_TEACHER')")
     */
     public function showMyStudentsAction()
     {
         // Je récupère l'ID de l'utilisateur connecté
         $userId = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $students = $em->getRepository('AppBundle:Subscription')->showMyStudentsAction($userId);

        return $this->render('default/student.html.twig', [
            'students' => $students,
        ]);
     }

    /**
    * @Route("/show/mySubscriptions", name="show_mySubscriptions")
    * @Security("has_role('ROLE_STUDENT')")
    */
    public function showMymySubscriptionsAction()
    {
        // Je récupère l'ID de l'utilisateur connecté
        $userId = $this->getUser()->getId();
        $em = $this->getDoctrine()->getManager();
        $subscriptions = $em->getRepository('AppBundle:Subscription')->showMySubscriptions($userId);

        return $this->render('default/subscriptions.html.twig', [
        'subscriptions' => $subscriptions,
        ]);
    }


    /**
     * @Route("showTeachers", name="show_teachers")
     */
     public function showTeachersAction()
     {
         $em = $this->getDoctrine()->getManager();
         $teachers = $em->getRepository('AppBundle:User')->showTeachers();
         dump($teachers);
         exit;
     }



     /**
      * @Route("/all/observations/{id}/{student}/{teacher}/{speciality}", name="all_observations")
      * @Security("has_role('ROLE_TEACHER') or has_role('ROLE_STUDENT')")
      */
      public function allObservationAction(Request $request, $id)
      {
        $student = $request->get('student');
        $teacher = $request->get('teacher');
        $speciality = $request->get('speciality');
        $id = $request->get('id');
        $em = $this->getDoctrine()->getManager();
        $result = $em->getRepository('AppBundle:Lesson')->showAllObservations($id);
        // dump($result);
        // exit;
        return $this->render('default/observation.html.twig', [
            'result' => $result,
            'student'=>$student,
            'teacher'=>$teacher,
            'speciality'=>$speciality
        ]);
      }


}
