<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 03.01.2019
 * Time: 12:04
 */

namespace App\Controller;


use App\Entity\Favourites;
use App\Entity\Guests;
use App\Entity\MyUser;
use App\Entity\Pets;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FavouriteController extends AbstractController
{

    /**
     * @param Pets $pet
     * @Route("/addfavourite/{pet}", requirements={"pet" = "\d+"}, name="dodajDoUlubionych")
     * @return RedirectResponse
     */
    public function addToFavourite(Pets $pet){
        $entityManager = $this->getDoctrine()->getManager();
        $email = $this->getUser()->getEmail();
        $logging = $this->getDoctrine()->getRepository(MyUser::class)->findOneBy(array(
            "email" => $email
        ));
        $guest = $this->getDoctrine()->getRepository(Guests::class)->findOneBy(array(
            "userId" => $logging->getId()
        ));

        $favourite = new Favourites();
        $favourite->setPet($pet);
        $favourite->setGuest($guest);

        $entityManager->persist($favourite);
        $entityManager->flush();

        return $this->redirectToRoute('wyswietl_zwierzaka', array(
            "id" => $pet->getPetId()
        ));
    }

    /**
     * @param Pets $pet
     * @Route("/removefav/{pet}", requirements={"pet" = "\d+"}, name="usunZUlubionych")
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeFromFavourite(Pets $pet){
        $entityManager = $this->getDoctrine()->getManager();
        $logging = $this->getDoctrine()->getRepository(MyUser::class)->findOneBy(array(
            "email" => $this->getUser()->getEmail()
        ));
        $guest = $this->getDoctrine()->getRepository(Guests::class)->findOneBy(array(
            "userId" => $logging->getId()
        ));
        $favourite = $this->getDoctrine()->getRepository(Favourites::class)->findOneBy(array(
                "pet" => $pet->getPetId(),
                "guest" => $guest->getGuestId()
            )
        );

        $entityManager->remove($favourite);
        $entityManager->flush();

        $favourites = $this->getDoctrine()->getRepository(Favourites::class)->findBy(array(
            "guest" => $guest->getGuestId()
        ));

        if(!$favourites)
            return $this->redirectToRoute('wyswietl_zwierzaki');
        else
            return $this->redirectToRoute('pokazUlubione');
    }


    /**
     * @Route("showfav", name="pokazUlubione")
     */
    public function showFavourite(){
        $logging = $this->getDoctrine()->getRepository(MyUser::class)->findOneBy(array(
            "email" => $this->getUser()->getEmail()
        ));
        $guest = $this->getDoctrine()->getRepository(Guests::class)->findOneBy(array(
            "userId" => $logging->getId()
        ));
        $favourites = $this->getDoctrine()->getRepository(Favourites::class)->findBy(array(
            "guest" => $guest->getGuestId()
        ));

        $repository = $this->getDoctrine()->getRepository(Pets::class);

        $zwierzaki = array();
        foreach ($favourites as $fav){
            $pet = $repository->findOneBy(array(
                "petId" => $fav->getPet()
            ));

            $tablica['nazwa'] = $pet->getName();
            $tablica['nr'] = $pet->getNumber();
            $tablica['zdjecie'] = $pet->getMainPic();
            $tablica['stan'] = $pet->getState();
            $data = $pet->getDateOfBirth();
            $tablica['data_ur'] = $data->format('d-m-Y');
            $tablica['id'] = $pet->getPetId();

            $zwierzaki[] = $tablica;
        }

        return $this->render('showFavourite.html.twig', array(
            "zwierzaki" => $zwierzaki
        ));
    }
}