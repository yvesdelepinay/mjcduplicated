<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use AppBundle\Entity\Lesson;
use AppBundle\Entity\User;
use AppBundle\Entity\Specialty;
use AppBundle\Entity\Subscription;
use AppBundle\Controller\FerieController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ImportSubscriptionCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        // Name and description for bin/console command
        $this
        ->setName('import:sub')
        ->setDescription('Import subscriptions from CSV file');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // Showing when the script is launched
        $now = new \DateTime();
        $output->writeln('<comment>Start : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');

        // Importing CSV on DB via Doctrine ORM
        $this->import($input, $output);

        // Showing when the script is over
        $now = new \DateTime();
        $output->writeln('<comment>End : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }

    protected function import(InputInterface $input, OutputInterface $output)
    {
        // Getting php array of data from CSV
        $data = $this->get($input, $output);

        // Getting doctrine manager
        $em = $this->getContainer()->get('doctrine')->getManager();
        // Turning off doctrine default logs queries for saving memory
        $em->getConnection()->getConfiguration()->setSQLLogger(null);

        // Define the size of record, the frequency for persisting the data and the current index of records
        $size = count($data);
        $batchSize = 10;
        $i = 1;

        // Starting progress
        $progress = new ProgressBar($output, $size);
        $progress->start();

        // Processing on each row of data
        foreach($data as $row) {
        /* For subscription,no need to check if they already exist because there is none from the start, the import is used to register all registrations at the beginning of a year
        */
            $subscription = $em->getRepository('AppBundle:Subscription')
                       ->findOneByStudent($row['sub_student']);
            $teacher = $em->getRepository('AppBundle:User')
                        ->findOneById($row['sub_teacher']);
            $student = $em->getRepository('AppBundle:User')
                                    ->findOneById($row['sub_student']);
            $speciality = $em->getRepository('AppBundle:Specialty')
                                    ->findOneById($row['sub_speciality']);


            // If the user doest not exist we create one
            if(!is_object($subscription)){
                $subscription = new Subscription();
                // $subscription->setEmail($row['email']);
            }

            // $subscription = new Subscription();

            //teacher_id, student_id & speciality
            $subscription->setTeacher($teacher);
            $subscription->setStudent($student);
            $subscription->setSpecialties($speciality);


            // current date
            $subscription->setSubscriptionAt(new \DateTime());

            // Je récupère la date du 1er cours
            $startAt = new \DateTime($row['sub_start']);
            $subscription->setStartAt($startAt);
            // Je récupère la durée d'un cours en seconde
            $duration = $row['duration'];
            // Je fais mon calcul pour ma date de fin (ici duration est enregistrée en int avec le nombre de secondes)
            $durationforDate = 'PT0H'.$duration .'S';

            //'PT0H1800S'=30min
            // L'objet Datetime a dû être passé "avec"
            // Il faut donc recréer un objet nouveau à partir d'une chaine date...
            // dump($startAt->format('Y-m-d H:i:s'));
            $startDate = new \Datetime($startAt->format('Y-m-d H:i:s'));
            $finishAt = $startDate;
            $finishAt->add(new \DateInterval($durationforDate));

            // Je mets à jour ma date de fin avec set
            $subscription->setFinishAt($finishAt);

        //No appreciation when we create a subscription

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
        //
         //Je récupère la startAt
         $date = $lesson->getStartAt();
         // dump($date);
         $newDate = '';
         // Tant que la date est + petite que la date des vacances
        //
         while ($date <= $holidayDate) {
             //J'ajoute 7 jours à ma date
             $date->modify('+7 day');
         //    dump($date);

             //Je mets la nouvelle date en timestamp pour vérifier qu'elle n'est pas un jour férié et pas pendant les vacances.
            $timestampDate = $date->getTimestamp();
            if ($date < $holidayDate) {
             //dump($timestampDate);
                 if (FerieController::estFerie($timestampDate)){
                     $date->modify('+7 day');
                     if (FerieController::estFerie($timestampDate)){
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
        /*Fin du Copier/coller*/


			// Each 20 users persisted we flush everything
            if (($i % $batchSize) === 0) {

                $em->flush();
				// Detaches all objects from Doctrine for memory save
                $em->clear();

				// Advancing for progress display on console
                $progress->advance($batchSize);

                $now = new \DateTime();
                $output->writeln(' of subscriptions imported ... | ' . $now->format('d-m-Y G:i:s'));

            }

            $i++;

        }

		// Flushing and clear data on queue
        $em->flush();
        $em->clear();

		// Ending the progress bar process
        $progress->finish();
    }

    protected function get(InputInterface $input, OutputInterface $output)
    {
        // Getting the CSV from filesystem
        $fileName = 'web/uploads/import/subsEssai.csv';

        // Using service for converting CSV to PHP Array
        $converter = $this->getContainer()->get('import.subtoarray');
        $data = $converter->convert($fileName, ',');

        return $data;
    }

}
