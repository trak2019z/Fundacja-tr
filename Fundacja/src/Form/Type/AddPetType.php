<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 06.12.2018
 * Time: 13:11
 */

namespace App\Form\Type;


use App\Entity\Pets;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class AddPetType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('daneZwierzaka', PetInfoType::class, array(
                'data_class' => Pets::class
            ))
            ->add('zdjecieZwierzaka', FileType::class, array(
                'label' => 'Wybierz zdjęcie główne zwierzaka'
            ))
            ->add('savePet', SubmitType::class, array(
                'label' => 'Zapisz zwierzaka'
            ));
    }
}