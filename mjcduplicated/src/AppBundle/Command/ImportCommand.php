<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Validator\Constraints\DateTime;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ImportCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        // Name and description for app/console command
        $this
        ->setName('import:csv')
        ->setDescription('Import users from CSV file');
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
        $batchSize = 20;
        $i = 1;

        // Starting progress
        $progress = new ProgressBar($output, $size);
        $progress->start();

        // Processing on each row of data
        foreach($data as $row) {

            $user = $em->getRepository('AppBundle:User')
                       ->findOneByEmail($row['email']);

			// If the user doest not exist we create one
            if(!is_object($user)){
                $user = new User();
                $user->setEmail($row['email']);
            }

    	// Updating info
        $user->setLastName($row['lastname']);
        $user->setFirstName($row['firstname']);
    	$user->setUsername($row['pseudo']);

        //J'encode le mot de passe
        // $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        // $plainpassword = $row["password"];
        // $password = $encoder->encodePassword($plainpassword, $user->getSalt());

        //J'appelle le service pour encoder
        $encoder = $this->getContainer()->get('security.password_encoder');
        //Je récupère le mot de passe de l'utilisateur à l'inscription
        $password = $row['password'];
        //J'encode le mot de passe
        $encoded = $encoder->encodePassword($user, $password);
        //Je fais l'update
        $user->setPassword($encoded);

		// $user->setPassword($row['password']);
		$user->setRole($row['role']);

        // On transforme ici la date en objet dateTime
        $format = 'd-m-Y';
        $birthDate = new \DateTime($row['birthday']);
        $user->setBirthAt($birthDate);


			// Do stuff here !

			// Persisting the current user
            $em->persist($user);

			// Each 20 users persisted we flush everything
            if (($i % $batchSize) === 0) {

                $em->flush();
				// Detaches all objects from Doctrine for memory save
                $em->clear();

				// Advancing for progress display on console
                $progress->advance($batchSize);

                $now = new \DateTime();
                $output->writeln(' of users imported ... | ' . $now->format('d-m-Y G:i:s'));

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
        $fileName = 'web/uploads/import/nouveaux.csv';

        // Using service for converting CSV to PHP Array
        $converter = $this->getContainer()->get('import.csvtoarray');
        $data = $converter->convert($fileName, ',');

        return $data;
    }

}
