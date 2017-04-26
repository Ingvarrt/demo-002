<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UsersSearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'First Name',
                'required' => false,
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Last Name',
                'required' => false,
                ])
            ->add('birthday', DateType::class, [
                'widget' => 'single_text',
                // do not render as type="date", to avoid HTML5 date pickers
                'html5' => false,
                // add a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker'],
                'required' => false,
                ])
            ->add('age', ChoiceType::class, [
                'label' => 'Age',
                'choices' => array_combine(range(5, 100, 5), range(5, 100, 5)),
                'required' => false,
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,])
            ->add('homeAddress', TextType::class, [
                'label' => 'Home Address',
                'required' => false,])
            ->add('companyAddress', TextType::class, [
                'label' => 'Company Address',
                'required' => false,])
            ->add('phone', TextType::class, [
                'label' => 'Phone',
                'required' => false,])
            ->add('companyName', TextType::class, [
                'label' => 'Company Name',
                'required' => false,])
            ->add('jobPosition', TextType::class, [
                'label' => 'Job Position',
                'required' => false,])
            ->add('cv', TextType::class, [
                'label' => 'CV',
                'required' => false,])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
        ));
    }
}