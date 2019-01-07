<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 12.12.2018
 * Time: 16:42
 */

namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PetInfoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $datePocz = date('Y') - 40;
        $dateKon = date('Y');
        $builder
            ->add('nazwaZwierzaka', TextType::class, array(
                'label' => "Wprowadź nazwę zwierzaka"
            ))
            ->add('numerZwierzaka', TextType::class, array(
                'label' => "Wprowadź numer zwierzaka",
                'required' => false
            ))
            ->add('opisZwierzaka', TextareaType::class, array(
                'label' => 'Wprowadź opis zwierzaka',
                'attr' => array('maxlength' => 5000)
            ))
            ->add('dataUrodzeniaZwierzaka', TextType::class, array(
                'label' => 'Wybierz datę urodzenia zwierzaka'
            ))
            ->add('stanZwierzaka', ChoiceType::class, array(
                'choices' => array(
                    'Do adopcji' => 'dostępny',
                    'Adoptowany' => 'adoptowany',
                    'W trakcie leczenia' => 'leczony',
                    'Odszedł' => 'zmarł'
                )
            ))
            ->add('dataAdopcji', TextType::class, array(
                'required' => false
            ))
            ->add('dataSmierci', TextType::class, array(
                'required' => false
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true,
        ));
    }


}