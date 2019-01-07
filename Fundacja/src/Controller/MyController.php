<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 05.12.2018
 * Time: 11:51
 */

namespace App\Controller;

use App\Entity\MyUser;
use App\Entity\Permissions;
use App\Entity\Pets;
use App\Entity\Workers;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MyController extends AbstractController
{

    /**
     * @Route("/", name="stronaGlowna")
     */
    public function mainPage(){
        return $this->render('mainpage.html.twig');
    }


    /**
     * @Route("/account", name="account")
     */
    public function userAccount(){
        $logging = $this->getDoctrine()->getRepository(MyUser::class)->findOneBy(array(
            "email" => $this->getUser()->getEmail()
        ));

        $typ = $logging->getTypeOfAccount();

        if($typ === "worker"){
            $worker = $this->getDoctrine()->getRepository(Workers::class)->findOneBy(array(
                "userId" => $logging->getId()
            ));
            $permission = $this->getDoctrine()->getRepository(Permissions::class)->findOneBy(array(
                "worker" => $worker
            ));
            $tablica['dodajzwierzaka'] = $permission->getAddPets();
            $tablica['edytujzwierzaka'] = $permission->getEditPets();
            $tablica['akceptujrezerwacje'] = $permission->getAcceptReservation();


            return $this->render("myaccount.html.twig", array(
                "konto" => $typ,
                "uprawnienia" => $tablica
            ));
        }
        else {
            return $this->render("myaccount.html.twig", array(
                "konto" => $typ
            ));
        }


    }

}