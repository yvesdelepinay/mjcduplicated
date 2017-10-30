<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\DataTransformerInterface;

class SubscriptionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('startAt', null, [
          'label' => 'début du premier cours',
          'format' => 'dd-MM-yyyy HH:mm',
          'years' => range(date('Y'), date('Y') + 2),
        //   'months' => range(date('m'), date('m') + 12),
        //   'days' => range(1,31),
        //   'hours' => range(1, 24),
          'placeholder' => [
              'day'=> 'Jour', 'month'=>'Mois', 'year'=>'Année', 'hour'=>'heure', 'minute'=>'Minute'
          ],
          // 'html5' => true,
        ])

        ->add('duration',  ChoiceType::class, [
          'label' => 'durée',
          'choices'  => array(
          '30 min' => 1800,
          '45 min' => 2700,
          '1h' => 3600,
          '1h30' => 6400,
          '2h' => 7200,
        )
        ])
        // Resta à mettre la date en français
        ->add('teacher', null, [
          'label' => 'Professeur',
        ])

        ->add('teacher', EntityType::class, [
            'class'=>'AppBundle:User',
            // 'choice_label'=>'firstname',
            'label'=> 'Professeur',

            'query_builder'=>function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                // Problème pour la concatenation firstname + lastname => Réussi en faisant la concaténation dans la méthode __toString() de mon entité User
                // ->addSelect ("CONCAT(u.firstname, u.lastname) as fullName")
                ->orderBy('u.firstname' , 'ASC')
                ->where("u.role = 'ROLE_TEACHER'");
            }
        ])

        ->add('student', EntityType::class, [
          'label' => 'Elève',
          'class' => 'AppBundle:User',
          'choice_label' => 'username',
        ])
        ->add('student', EntityType::class, [
            'class'=>'AppBundle:User',
            // 'choice_label'=>'firstname',
            'label'=> 'Elève',

            'query_builder'=>function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                // Problème pour la concatenation firstname + lastname
                // ->addSelect ("CONCAT(u.firstname, u.lastname)")
                ->orderBy('u.firstname' , 'ASC')
                ->where("u.role = 'ROLE_STUDENT'");
                // ->getQuery();
                // ->getResult();
            }
        ])

        ->add('specialties', null, [
          'label' => 'spécialité',
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Subscription',
            'attr' => ['novalidate' => 'novalidate'],

        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_subscription';
    }


 private function showTeachers()
   {
       return function(EntityRepository $er)
       {
           return $er->createQueryBuilder('u')
           ->where('u.role = "ROLE_TEACHER"');
        //    ->getQuery();
       }
    ;}

    //  $query = $this->getEntityManager()->createQuery(
    //      "SELECT u FROM AppBundle:User u
    //      WHERE u.role = 'ROLE_TEACHER'"
    //  );
    //  return $query;

}
