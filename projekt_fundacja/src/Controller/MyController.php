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
use Symfony\Component\HttpFoundation\Response;

class MyController extends AbstractController
{
    /**
     * @Route("test", name="strona_zwierzaki")
     */
    public function showPetInfo(){
        return $this->render('aboutPets.html.twig');
    }

}