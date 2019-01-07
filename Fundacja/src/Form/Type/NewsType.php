<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 12.12.2018
 * Time: 17:31
 */

namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class NewsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tytul', TextType::class, array(
                'label' => 'Wprowadź tytuł',
                'required' => false,
            ))
            ->add('opis', TextareaType::class, array(
                'label' => 'Wprowadź opis:',
            ))
            ->add('zapisz', SubmitType::class, array(
                'label' => 'ZAPISZ'
            ));

    }

}