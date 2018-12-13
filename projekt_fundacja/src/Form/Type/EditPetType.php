<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 12.12.2018
 * Time: 16:52
 */

namespace App\Form\Type;


use App\Entity\Pets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class EditPetType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('daneZwierzaka', PetInfoType::class, array(
                'data_class' => Pets::class,
            ))
            ->add('edytuj', SubmitType::class, array(
                'label' => 'AKTUALIZUJ'
            ));
    }

}