<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 06.01.2019
 * Time: 15:10
 */

namespace App\Controller;


use App\Entity\Guests;
use App\Entity\MyUser;
use App\Entity\Pets;
use App\Entity\Reservations;
use App\Form\Type\EditReservationType;
use App\Form\Type\ReservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class ReservationController extends AbstractController
{
    /**
     * @param Pets $pet
     * @Route("/reservation/{pet}", requirements={"pet" = "\d+"}, name="dodajRezerwacje")
     */
    public function addReservation(Pets $pet){
        $logging = $this->getDoctrine()->getRepository(MyUser::class)->findOneBy(array(
            "email" => $this->getUser()->getEmail()
        ));
        $guest = $this->getDoctrine()->getRepository(Guests::class)->findOneBy(array(
            "userId" => $logging->getId()
        ));

        $entityManager = $this->getDoctrine()->getManager();
        $reservation = new Reservations();
        $reservation->setGuest($guest);
        $reservation->setPet($pet);
        $entityManager->persist($reservation);
        $entityManager->flush();

        return $this->redirectToRoute('wyswietl_zwierzaka', array(
            "id" => $pet->getPetId()
        ));

    }


    /**
     * @Route("/showres", name="wyswietlRezerwacje")
     */
    public function showGuestReservation(){
        $logging = $this->getDoctrine()->getRepository(MyUser::class)->findOneBy(array(
            "email" => $this->getUser()->getEmail()
        ));
        $guest = $this->getDoctrine()->getRepository(Guests::class)->findOneBy(array(
            "userId" => $logging->getId()
        ));

        $rezerwacja = $this->getDoctrine()->getRepository(Reservations::class)->findOneBy(array(
            "guest" => $guest
        ));

        if($rezerwacja){
            $repository = $this->getDoctrine()->getRepository(Pets::class);

            $pet = $repository->findOneBy(array(
                "petId" => $rezerwacja->getPet()
            ));

            if(!$rezerwacja->getToWhen() == null)
            {
                $data = $rezerwacja->getToWhen();
                $rezerwacjaZwierzaka['dokiedy'] = $data->format('d-m-Y');
            }


            $rezerwacjaZwierzaka['status'] = $rezerwacja->getAccepted();

            $tablica['nazwa'] = $pet->getName();
            $tablica['nr'] = $pet->getNumber();
            $tablica['zdjecie'] = $pet->getMainPic();
            $tablica['stan'] = $pet->getState();
            $data = $pet->getDateOfBirth();
            $tablica['data_ur'] = $data->format('d-m-Y');
            $tablica['id'] = $pet->getPetId();

            return $this->render('showReservationGuest.html.twig', array(
                "zwierzaki" => $tablica,
                "rezerwacja" => $rezerwacjaZwierzaka
            ));

        }
        else
            return $this->render('showReservationGuest.html.twig', array(
            ));




    }

    /**
     * @Route("showreservation", name="wyswietlWszystkieRezerwacje")
     */
    public function showReservations(){
        $reservation = $this->getDoctrine()->getRepository(Reservations::class)->findAll();
        $rezerwacje = array();
        $repository = $this->getDoctrine()->getRepository(Pets::class);
        foreach ($reservation as $reserv){
            $pet = $repository->findOneBy(array(
                "petId" => $reserv->getPet()
            ));

            $guest = $this->getDoctrine()->getRepository(Guests::class)->findOneBy(array(
                "guestId" => $reserv->getGuest()
            ));

            $tablica['nazwa'] = $pet->getName();
            $tablica['numer'] = $pet->getNumber();
            $tablica['glowne'] = $pet->getMainPic();
            $tablica['status'] = $reserv->getAccepted();
            $tablica['id'] = $reserv->getReservationId();

            $usun = "nie";

            if(!$reserv->getToWhen() == null)
            {
                $data = $reserv->getToWhen();
                $dzis = new \DateTime();
                if($dzis > $data)
                    $usun = "tak";
                else
                    $usun = "nie";
                $tablica['dokiedy'] = $data->format('d-m-Y');
            }

            $tablica['usun'] = $usun;
            $tablica['imie'] = $guest->getName();
            $tablica['nazwisko'] = $guest->getSurname();

            $rezerwacje[] = $tablica;
        }

        return $this->render('showReservation.html.twig', array(
            'rezerwacje' => $rezerwacje
        ));

    }

    /**
     * @param Reservations $id
     * @Route("/reservationstate/{id}", requirements={"id" = "\d+"}, name = "akceptacjarezerwacji")
     * @return RedirectResponse
     */
    public function acceptReservation(Request $request, Reservations $id){
        $form = $this->createForm(ReservationType::class);
        $entityManager = $this->getDoctrine()->getManager();
        $rezerwacja = $this->getDoctrine()->getRepository(Reservations::class)->find($id);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $stan = $form->get('rezerwacja')->getData();
            if($stan === "accept"){
                $rezerwacja->setAccepted($stan);
                $data = new \DateTime($form->get('dokiedy')->getData());
                $rezerwacja->setToWhen($data);
                $entityManager->flush();
            }
            else{
                $entityManager->remove($rezerwacja);
                $entityManager->flush();
            }

            return $this->redirectToRoute('wyswietlWszystkieRezerwacje');
        }

        return $this->render('reservationState.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Reservations $id
     * @Route("/addtime/{id}", requirements={"id" = "\d+"}, name = "przedluzRezerwacje")
     * @return RedirectResponse
     */
    public function addMoreTime(Request $request, Reservations $id){
        $form = $this->createForm(EditReservationType::class);
        $entityManager = $this->getDoctrine()->getManager();
        $rezerwacja = $this->getDoctrine()->getRepository(Reservations::class)->find($id);

        $data =  $rezerwacja->getToWhen();
        $form->get("dokiedy")->setData($data->format('Y-m-d'));
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
                $data = new \DateTime($form->get('dokiedy')->getData());
                $rezerwacja->setToWhen($data);
                $entityManager->flush();

            return $this->redirectToRoute('wyswietlWszystkieRezerwacje');
        }

        return $this->render('editReservation.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @param Reservations $id
     * @Route("/removeres/{id}", requirements={"id" = "\d+"}, name="usunrezerwacje")
     * @return RedirectResponse
     */
    public function removeReservation(Reservations $id){
        $entityManager = $this->getDoctrine()->getManager();
        $rezerwacja = $this->getDoctrine()->getRepository(Reservations::class)->find($id);
        $entityManager->remove($rezerwacja);
        $entityManager->flush();

        return $this->redirectToRoute('wyswietlWszystkieRezerwacje');
}



}