<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 07.01.2019
 * Time: 12:28
 */
namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class EditReservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options); // TODO: Change the autogenerated stub
        $builder

            ->add('dokiedy', TextType::class, array(
                'label' => "Do kiedy ważna rezerwacja."
            ))
            ->add('zapisz', SubmitType::class, array(
                'label' => "ZATWIERDŹ"
            ))
        ;
    }
}