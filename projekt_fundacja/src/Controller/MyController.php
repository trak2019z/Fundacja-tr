<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 05.12.2018
 * Time: 11:51
 */

namespace App\Controller;

use App\Entity\Pets;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;

class MyController extends AbstractController
{

    /**
     * @Route("/pet", name="pet")
     */

    public function addPet(){
        $entityManager = $this->getDoctrine()->getManager();

        $pet = new Pets();
        $pet->setName('Cookie');
        $pet->setNumber('289/2017');
        $pet->setDescription('Kocur, niekastrowany, zaszczepiony, odrobaczony, zaczipowany. Posiada rodowód. Rodowód wydawany po kastracji.');
        $pet->setMainPic('pic2');
        $pet->setDateOfBirth(\DateTime::createFromFormat('Y-m-d', "2018-06-22"));
        $pet->setState('adopted');

        $entityManager->persist($pet);
        $entityManager->flush();

        return new Response('Dodano zwierzaka o id:'.$pet->getPetId());
    }

    /**
     * @Route("/pet/{id}", name="show_pet_id")
     */
    public function showPet($id){
        $pet = $this->getDoctrine()->getRepository(Pets::class)->find($id);
        if(!$pet){
            throw $this->createNotFoundException(
                'Nie ma zwierzaka w bazie o id: '.$id
            );
        }

        return new Response("Nazwa szukanego zwierzaka to: ".$pet->getName()." jego numer to: " . $pet->getNumber());
    }

    /**
     * @Route("/form", name="formularz_test")
     */
    public function formMaker(){
        $form = $this->createFormBuilder()->add('imie:', TextType::class)
            ->add("numer", TextType::class, array('label'=> "Numer zwierzaka"))
            ->add("data", DateType::class)
            ->add("opis:", TextareaType::class)
            ->add("zdjecie", FileType::class )
            ->add("dodaj", SubmitType::class)
            ->getForm();

        return $this->render('formtest.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}