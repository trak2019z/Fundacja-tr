<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 12.12.2018
 * Time: 18:17
 */

namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class DebtType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('kwota', NumberType::class, array(
                'label' => "Wpisz kwotę długu",
            ))
            ->add('zapisz', SubmitType::class, array(
                'label' => 'ZAPISZ DŁUG'
            ));
    }

}