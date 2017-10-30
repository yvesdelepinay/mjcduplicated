<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class UserTypeEdit extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username', null, [
           'label'  => 'Pseudo',
           ])
        ->add('firstname', null,  [
           'label'  => 'Prénom',
           ])
        ->add('lastname', null,  [
           'label'  => 'Nom',
           ])
        ->add('email')
        // ->add('password', PasswordType::class,  [
        //    'label'  => 'Mot de passe',
        //    ])
        ->add('password', RepeatedType::class, array(
    'type' => PasswordType::class,
    'invalid_message' => 'Le mot de passe doit être le même dans les 2  champs.',
    'options' => array('attr' => array('class' => 'password-field')),
    'required' => false,
    'attr' => ['placeholder' => 'Laissez vide si inchangé'],
    'first_options'  => array('label' => 'Mot de passe'),
    'second_options' => array('label' => 'Confirmation du mot de passe'),
))
        ->add('birthAt', BirthdayType::class, [
           'format' => 'dd-MM-yyyy',
           'label'  => 'date de naissance',
           'placeholder' => [
               'day'=> 'Jour', 'month'=>'Mois', 'year'=>'Année'
           ]
        ])
        ->add('role',ChoiceType::class, [
          'choices' => [
            'Elève' => 'ROLE_STUDENT',
            'Professeur' => 'ROLE_TEACHER',
          ]
        ])
        ->add('isActive');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User',
            'attr' => ['novalidate' => 'novalidate']
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_user';
    }


}
