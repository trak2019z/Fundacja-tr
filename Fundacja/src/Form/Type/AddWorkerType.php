<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 05.12.2018
 * Time: 22:11
 */

namespace App\Form\Type;

use App\Entity\MyUser;
use App\Entity\Permissions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AddWorkerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = date('Y') - 18;
        $builder
            ->add('imiePracownika', TextType::class, array(
                'label' => 'Wprowadź imię pracownika'
            ))
            ->add('nazwiskoPracownika', TextType::class, array(
                'label' => 'Wprowadź nazwisko pracownika'
            ))
            ->add('dataUrodzenia', TextType::class, array(
                'label' => 'Data urodzenia pracownika'
            ))
            ->add('pozycjaPracownika', ChoiceType::class, array(
                'choices' => array(
                    'Kierownik' => 'kierownik',
                    'Zastępca kierownika' => 'zastepca',
                    'Starszy pracownik' => 'pracownikS',
                    'Pracownik' => 'pracownik',
                    'Stażysta' => 'stazysta',
                    'Wolontariusz' => 'wolontariusz'
                )
            ))
            ->add('daneLogowania', RegisterUserType::class, array(
                'data_class' => MyUser::class,
            ))
            ->add('checkboxPracownik', PermissionsType::class, array(
                'data_class' => Permissions::class,
                'label' => "Uprawnienia pracownika"

            ))
            ->add('zapiszPracownika', SubmitType::class, array(
                'label' => "Zapisz pracownika"
            ))
        ;
    }

}




