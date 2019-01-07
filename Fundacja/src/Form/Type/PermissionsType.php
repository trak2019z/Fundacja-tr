<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 06.12.2018
 * Time: 09:56
 */

namespace App\Form\Type;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermissionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('checkDodajZwierze', CheckboxType::class, array(
                'label' => "Dodawanie zwierząt",
                'required' => false
            ))
            ->add('checkEdycjaZwierzat', CheckboxType::class, array(
                'label' => "Modyfikacja danych zwierząt",
                'required' => false
            ))
            ->add('checkUsunZwierze', CheckboxType::class, array(
                'label' => "Usuwanie zwierząt z bazy",
                'required' => false
            ))
            ->add('checkPrzeniesZwierze', CheckboxType::class, array(
                'label' => "Edycja dodatkowych informacji",
                'required' => false
            ))
            ->add('checkDodajDlug', CheckboxType::class, array(
                'label' => "Dodawanie długów zwierząt",
                'required' => false
            ))
            ->add('checkEdycjaDlugow', CheckboxType::class, array(
                'label' => "Modyfikacja długów zwierząt",
                'required' => false
            ))
            ->add('checkAkceptujRezerwacje', CheckboxType::class, array(
                'label' => "Akceptacja wstępnej rezerwacji zwierząt",
                'required' => false
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true
        ));
    }

}