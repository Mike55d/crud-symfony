<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;


class UsersMasterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('username', EmailType::class , ['label'=>'Email'])
        ->add('name', TextType::class,['label'=>'Nombre'])
        ->add('plainPassword', RepeatedType::class, array(
            'type' => PasswordType::class,
            'first_options' => array('label' => 'Contraseña'),
            'second_options' => array('label' => 'Repetir contraseña')))
        ->add('active',CheckboxType::class,['data'=>true,'label'=>'Activo'])
        ->add('phone',TextType::class,['label'=>'Telefono'])
        ->add('telefonero')
        ->add('sede',null,['required'=>true])
        ->add('image',FileType::class,['label'=>'Imagen'])
        ->add('roles',ChoiceType::class,['choices'=>['ADMIN'=>'ROLE_ADMIN',
                                                     'USER'=>'ROLE_USER']])
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Users',
            'allow_extra_fields' => true
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return null;
    }


}
