<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 02.01.2019
 * Time: 22:51
 */

namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EditUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options); // TODO: Change the autogenerated stub
        $builder
            ->add("imie", TextType::class, array(
                'label' => "Wprowadź swoje imię:"
            ))
            ->add("nazwisko", TextType::class, array(
                "label" => "Wprowadź swoje nazwisko:"
            ))
            ->add("akceptuj", SubmitType::class, array(
                "label" => "ZAPISZ ZMIANY"
            ));
    }

}