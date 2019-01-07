<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 06.12.2018
 * Time: 13:09
 */

namespace App\Controller;

use App\Entity\Additionals;
use App\Entity\Adopted;
use App\Entity\Dead;
use App\Entity\Debts;
use App\Entity\Favourites;
use App\Entity\Guests;
use App\Entity\MyUser;
use App\Entity\Permissions;
use App\Entity\Pets;
use App\Entity\Photos;
use App\Entity\Reservations;
use App\Entity\Workers;
use App\Form\Type\AddPetType;
use App\Form\Type\EditPetType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Tests\Compiler\D;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PetController extends AbstractController
{

    /**
     * @Route("/animal",  name="dodanieZwierzakaFormularz")
     */
    public function addPet(Request $request){
        $pet = new Pets();
        $form = $this->createForm(AddPetType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager = $this->getDoctrine()->getManager();
            $file = $form->get('zdjecieZwierzaka')->getData();
            $nazwa = $file->getClientOriginalName();
            $nazwa = explode('.', $nazwa);
            $dlugosc = sizeof($nazwa);
            $fileName = $this->generateUniqueFileName() . '.' . $nazwa[$dlugosc-1] ;
            try{
                $file->move(
                    $this->getParameter('animals_photos'),
                    $fileName
                );
                $pet->setName($form->get('daneZwierzaka')->get('nazwaZwierzaka')->getData());
                $pet->setNumber($form->get('daneZwierzaka')->get('numerZwierzaka')->getData());
                $pet->setDescription($form->get('daneZwierzaka')->get('opisZwierzaka')->getData());
                $date = new \DateTime($form->get('daneZwierzaka')->get('dataUrodzeniaZwierzaka')->getData());
                $pet->setDateOfBirth($date);
                $pet->setState($form->get('daneZwierzaka')->get('stanZwierzaka')->getData());
                $pet->setMainPic($fileName);

                $entityManager->persist($pet);
                $entityManager->flush();

                if($form->get('daneZwierzaka')->get('stanZwierzaka')->getData() == 'adoptowany'){
                    $adopted = new Adopted();
                    $adopted->setPet($pet);
                    $data = new \DateTime($form->get('daneZwierzaka')->get('dataAdopcji')->getData());
                    $adopted->setDateOfAdoption($data);

                    $entityManager->persist($adopted);
                    $entityManager->flush();
                }
                elseif ($form->get('daneZwierzaka')->get('stanZwierzaka')->getData() == 'zmarł'){
                    $dead = new Dead();
                    $dead->setPet($pet);
                    $data = new \DateTime($form->get('daneZwierzaka')->get('dataSmierci')->getData());
                    $dead->setDateOfPassAway($data);

                    $entityManager->persist($dead);
                    $entityManager->flush();
                }

            }catch(\Exception $e){
                echo 'Nie udało się przenieść pliku, błąd: ' . $e;
            }

            $this->addFlash('success', 'pet.created_successfully');

            return $this->redirectToRoute('dodanieZwierzakaFormularz');
        }
        return $this->render('addPet.html.twig', array(
            'form' => $form->createView()
        ));
    }

    private function generateUniqueFileName(){
        return md5(uniqid());
    }

    /**
     * @Route("/pets", name="wyswietl_zwierzaki")
     */
    public function showPets(){
        $repository = $this->getDoctrine()->getRepository(Pets::class);
        $pets = $repository->findBy(array(
            'state' => array(
                'dostępny',
                'leczony' )));
        $zwierzaki = array();

        foreach ($pets as $pet){
            $tablica['nazwa'] = $pet->getName();
            $tablica['numer'] = $pet->getNumber();
            $tablica['zdjecie'] = $pet->getMainPic();
            $data = $pet->getDateOfBirth();
            $tablica['data'] = $data->format('d-m-Y');
            $tablica['stan'] = $pet->getState();
            $tablica['id'] = $pet->getPetId();
            $zwierzaki[] = $tablica;
        }

        return $this->render('showPets.html.twig', array(
            'zwierzaki' => $zwierzaki
        ));
    }

    /**
     * @Route("/adopted", name="wyswietl_zaadoptowane")
     */
    public function showAdopted(){
        $repository = $this->getDoctrine()->getRepository(Pets::class);
        $pets = $repository->findBy(array(
            'state' => 'adoptowany'));

        $zwierzaki = array();
        $repository = $this->getDoctrine()->getRepository(Adopted::class);
        foreach ($pets as $pet)
        {
            $adopted = $repository->findOneBy(array(
                'pet' => $pet->getPetId()
            ));
            $tablica['nazwa'] = $pet->getName();
            $tablica['numer'] = $pet->getNumber();
            $tablica['zdjecie'] = $pet->getMainPic();
            $data_ur = $pet->getDateOfBirth();
            $tablica['data_ur'] = $data_ur->format('d-m-Y');
            $data_ad = $adopted->getDateOfAdoption();
            $tablica['data_ad'] = $data_ad->format('d-m-Y');
            $tablica['id'] = $pet->getPetId();
            $zwierzaki[] = $tablica;
        }

        return $this->render('showAdoptedPets.html.twig', array(
            'zwierzaki' => $zwierzaki
        ));
    }

    /**
     * @Route("/dead", name="wyswietl_zmarle")
     */
    public function showDead(){
        $repository = $this->getDoctrine()->getRepository(Pets::class);
        $pets = $repository->findBy(array(
            'state' => 'zmarł'
        ));
        $repository = $this->getDoctrine()->getRepository(Dead::class);
        $zwierzaki = array();
        foreach ($pets as $pet){
            $dead = $repository->findOneBy(array(
                'pet' => $pet->getPetId()
            ));
            $tablica['nazwa'] = $pet->getName();
            $tablica['numer'] = $pet->getNumber();
            $tablica['zdjecie'] = $pet->getMainPic();
            $data_ur = $pet->getDateOfBirth();
            $tablica['data_ur'] = $data_ur->format('d-m-Y');
            $data_zm = $dead->getDateOfPassAway();
            $tablica['data_zm'] = $data_zm->format('d-m-Y');
            $tablica['id'] = $pet->getPetId();
            $zwierzaki[] = $tablica;
        }

        return $this->render('showDeadPets.html.twig',array(
            'zwierzaki' => $zwierzaki
        ));
    }

    /**
     * @param Pets $id
     *
     * @Route("{id}/editpets", requirements={"id" = "\d+"}, methods={"GET","POST"}, name="edycja_zwierzaka")
     * @return RedirectResponse
     */
    public function editPet(Request $request, Pets $id){
        $form = $this->createForm(EditPetType::class);
        $pet = $this->getDoctrine()->getRepository(Pets::class)->find($id->getPetId());

        if(!$pet){
            throw $this->createNotFoundException(
                'Nie ma zwierzaka w bazie o id: '.$id->getPetId()
            );
        }
        else{
            $form->get('daneZwierzaka')->get('nazwaZwierzaka')->setData($pet->getName());
            $form->get('daneZwierzaka')->get('numerZwierzaka')->setData($pet->getNumber());
            $form->get('daneZwierzaka')->get('opisZwierzaka')->setData($pet->getDescription());
            $data = $pet->getDateOfBirth();
            $form->get('daneZwierzaka')->get('dataUrodzeniaZwierzaka')->setData($data->format('Y-m-d'));
            $form->get('daneZwierzaka')->get('stanZwierzaka')->setData($pet->getState());
            if($pet->getState() === "adopted")
            {
                $adopted = $this->getDoctrine()->getRepository(Adopted::class)->findOneBy(array(
                    "pet" => $pet
                ));
                $data = $adopted->getDateOfAdoption();
                $form->get('daneZwierzaka')->get('dataAdopcji')->setData($data->format('Y-m-d'));
            }
            if($pet->getState() === "dead")
            {
                $dead = $this->getDoctrine()->getRepository(Dead::class)->findOneBy(array(
                    "pet" => $pet
                ));
                $data = $dead->getDateOfPassAway();
                $form = $this->get('daneZwierzaka')->get('dataSmierci')->set($data->format('Y-m-d'));
            }
        }

        $form->handleRequest($request);

        try{
            if($form->isSubmitted() && $form->isValid())
            {
                $entityManager = $this->getDoctrine()->getManager();
                $pet->setName($form->get('daneZwierzaka')->get('nazwaZwierzaka')->getData());
                $pet->setNumber($form->get('daneZwierzaka')->get('numerZwierzaka')->getData());
                $pet->setDescription($form->get('daneZwierzaka')->get('opisZwierzaka')->getData());
                $data = new \DateTime($form->get('daneZwierzaka')->get('dataUrodzeniaZwierzaka')->getData());
                $pet->setDateOfBirth($data);

                $pet->setState($form->get('daneZwierzaka')->get('stanZwierzaka')->getData());

                $entityManager->flush();

                if($form->get('daneZwierzaka')->get('stanZwierzaka')->getData() == 'adoptowany'){
                    $adopted = new Adopted();
                    $adopted->setPet($pet);
                    $data = new \DateTime($form->get('daneZwierzaka')->get('dataAdopcji')->getData());
                    $adopted->setDateOfAdoption($data);

                    $entityManager->persist($adopted);
                    $entityManager->flush();
                }
                elseif ($form->get('daneZwierzaka')->get('stanZwierzaka')->getData() == 'zmarł'){
                    $dead = new Dead();
                    $dead->setPet($pet);
                    $data = new \DateTime($form->get('daneZwierzaka')->get('dataSmierci')->getData());
                    $dead->setDateOfPassAway($data);

                    $entityManager->persist($dead);
                    $entityManager->flush();
                }

                $this->addFlash('success', 'pet.edited_successfully');
                return $this->redirectToRoute('wyswietl_zwierzaka', array(
                    'id' => $id->getPetId()
                ));
            }
        }catch (\Exception $e){
            $this->addFlash('error', $e);
        }

        return $this->render('editPets.html.twig', array(
            'form' => $form->createView(),
            'id' => $id->getPetId()
        ));
    }

    /**
     * @param Pets $id
     *
     * @Route("/{id}/removepet", requirements={"id" = "\d+"}, name="usuniecie_zwierzaka")
     * @return RedirectResponse
     */
    public function removePet(Pets $id){
        $pet = $this->getDoctrine()->getRepository(Pets::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $filesystem = new Filesystem();
        $additional = $this->getDoctrine()->getRepository(Additionals::class)->findBy(array(
            'pet' => $id
        ));
        $photos = $this->getDoctrine()->getRepository(Photos::class)->findOneBy(array(
            'pet' => $id
        ));

        if($pet->getState() == 'adoptowany'){
            $adopted = $this->getDoctrine()->getRepository(Adopted::class)->findOneBy(array(
                'pet' => $id
            ));
            try{
                $filesystem->remove($pet->getMainPic());
                if($photos){
                    $entityManager->remove($photos);
                    $entityManager->flush();
                }

                if($additional) {
                    $entityManager->remove($additional);
                    $entityManager->flush();
                }
                $entityManager->remove($adopted);
                $entityManager->flush();
                $entityManager->remove($pet);
                $entityManager->flush();

                $this->addFlash('success', 'pet.removed_successfully');
            }catch(\Exception $e){
                $this->addFlash('error', $e);
            }
        }
        elseif ($pet->getState() == 'zmarł'){
            $dead = $this->getDoctrine()->getRepository(Dead::class)->findOneBy(array(
                'pet' => $id
            ));
            try{
                $filesystem->remove($pet->getMainPic());
                if($photos){
                    $entityManager->remove($photos);
                    $entityManager->flush();
                }

                if($additional) {
                    $entityManager->remove($additional);
                    $entityManager->flush();
                }

                $entityManager->remove($pet);
                $entityManager->flush();
                $entityManager->remove($dead);

                $entityManager->flush();

                $this->addFlash('success', 'pet.removed_successfully');
            }catch(\Exception $e){
                $this->addFlash('error', $e);
            }
        }
        else{
            try{
                $filesystem->remove($pet->getMainPic());
                if($photos){
                    $entityManager->remove($photos);
                    $entityManager->flush();
                }

                if($additional) {
                    $entityManager->remove($additional);
                    $entityManager->flush();
                }
                $entityManager->remove($pet);
                $entityManager->flush();

                $this->addFlash('success', 'pet.removed_successfully');
            }catch(\Exception $e){
                $this->addFlash('error', $e);
            }
        }

        return $this->redirectToRoute('wyswietl_zwierzaki');
    }

    /**
     * @param Pets $id
     *
     * @Route("/{id}/pet", requirements={"id" = "\d+"}, name="wyswietl_zwierzaka")
     * @return RedirectResponse
     */
    public function showMoreAboutPet(Pets $id){
        $pet = $this->getDoctrine()->getRepository(Pets::class)->find($id);
        $news = $this->getDoctrine()->getRepository(Additionals::class)->findBy(array(
            'pet' => $id
        ));


        $reserv = "nie";
        $typ = "";
        $uprawnienia = array();
        $ulubione = array();

        if($this->getUser())
        {
            $logging = $this->getDoctrine()->getRepository(MyUser::class)->findOneBy(array(
                "email" => $this->getUser()->getEmail()
            ));
            $typ = $logging->getTypeOfAccount();
            if($typ === "worker")
            {
                $worker = $this->getDoctrine()->getRepository(Workers::class)->findOneBy(array(
                    "userId" => $logging->getId()
                ));
                $permissions = $this->getDoctrine()->getRepository(Permissions::class)->findOneBy(array(
                    "worker" => $worker
                ));
                $uprawnienia['dodajzwierzaka'] = $permissions->getAddPets();
                $uprawnienia['edytujzwierzaka'] = $permissions->getEditPets();
                //pomyłka w bazie danych, zamiast newsy jest przeniesienie zwierząt.
                // ze względu na dużą problematykę podczas zmiany postanowiono zachować
                //nazwę oryginalną służącą do przenoszenia zwierząt
                $uprawnienia['newsy'] = $permissions->getMovePets();
                $uprawnienia['usunzwierzaka'] = $permissions->getDeletePets();
                $uprawnienia['dodajdlug'] = $permissions->getAddDebt();
                $uprawnienia['zmiendlug'] = $permissions->getChangeDebt();
            }
            else if($typ === "guest"){
                $guest = $this->getDoctrine()->getRepository(Guests::class)->findOneBy(array(
                    "userId" => $logging->getId()
                ));
                $fav = $this->getDoctrine()->getRepository(Favourites::class)->findBy(array(
                    "guest" => $guest->getGuestId()
                ));
                foreach ($fav as $f){
                    $ulub['id'] = $f->getPet();

                    $ulubione[] = $ulub;
                }
                $reserv = $this->getDoctrine()->getRepository(Reservations::class)->findOneBy(array(
                    "guest" => $guest
                ));
            }
        }

        if(!$reserv)
            $czyRezerwacja = "nie";
        else
            $czyRezerwacja = "tak";

        $reservation = $this->getDoctrine()->getRepository(Reservations::class)->findOneBy(array(
            "pet" => $pet
        ));

        if(!$reservation)
            $zarezerwowany = "nie";
        else
            $zarezerwowany = "tak";

        $photos = $this->getDoctrine()->getRepository(Photos::class)->findBy(array(
            'pet' => $id
        ));

        $debt = $this->getDoctrine()->getRepository(Debts::class)->findOneBy(array(
            "pet" => $id
        ));

        $dodatkowe = array();
        $zdjecia = array();
        $zwierzak = array();

        if(!$pet){
            throw $this->createNotFoundException(
                'No pet found for id '.$id->getPetId()
            );
        }
        elseif(!$news && !$photos && !$debt){
            $tablica['imie'] = $pet->getName();
            $tablica['numer'] = $pet->getNumber();
            $tablica['zdjecie'] = $pet->getMainPic();
            $tablica['opis'] = $pet->getDescription();
            $data = $pet->getDateOfBirth();
            $tablica['data_urodzenia'] = $data->format('d-m-Y');
            $tablica['stan'] = $pet->getState();
            $tablica['id'] = $pet->getPetId();
            if($tablica['stan'] == 'adoptowany'){
                $adopted = $this->getDoctrine()->getRepository(Adopted::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $adopted->getDateOfAdoption();
                $tablica['data_ad'] = $data->format('d-m-Y');
            }
            elseif($tablica['stan'] == 'zmarł'){
                $dead = $this->getDoctrine()->getRepository(Dead::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $dead->getDateOfPassAway();
                $tablica['data_zm'] = $data->format('d-m-Y');
            }
            $zwierzak = $tablica;

            return $this->render('showPet.html.twig', array(
                'zwierzak' => $zwierzak,
                'typ' => $typ,
                'uprawnienia' => $uprawnienia,
                'ulubione' => $ulubione,
                'czyRezerwacja' => $czyRezerwacja,
                'zarezerwowany' => $zarezerwowany
            ));
        }
        elseif (!$photos && !$debt){
            $tablica['imie'] = $pet->getName();
            $tablica['numer'] = $pet->getNumber();
            $tablica['zdjecie'] = $pet->getMainPic();
            $tablica['opis'] = $pet->getDescription();
            $data = $pet->getDateOfBirth();
            $tablica['data_urodzenia'] = $data->format('d-m-Y');
            $tablica['stan'] = $pet->getState();
            $tablica['id'] = $pet->getPetId();
            if($tablica['stan'] == 'adoptowany'){
                $adopted = $this->getDoctrine()->getRepository(Adopted::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $adopted->getDateOfAdoption();
                $tablica['data_ad'] = $data->format('d-m-Y');
            }
            elseif($tablica['stan'] == 'zmarł'){
                $dead = $this->getDoctrine()->getRepository(Dead::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $dead->getDateOfPassAway();
                $tablica['data_zm'] = $data->format('d-m-Y');
            }
            $zwierzak= $tablica;

            foreach ($news as $n){
                $data = $n->getNewsDate();
                $tab['data_n'] = $data->format('d-m-Y');
                $tab['tytul'] = $n->getTitle();
                $tab['opis'] = $n->getDescription();
                $tab['id'] = $n->getNewsId();

                $dodatkowe[] = $tab;
            }

            return $this->render('showPet.html.twig', array(
                'zwierzak' => $zwierzak,
                'dodatkowe' => $dodatkowe,
                'typ' => $typ,
                'uprawnienia' => $uprawnienia,
                'ulubione' => $ulubione,
                'czyRezerwacja' => $czyRezerwacja,
                'zarezerwowany' => $zarezerwowany
            ));

        }
        elseif (!$news && !$debt){
            $tablica['imie'] = $pet->getName();
            $tablica['numer'] = $pet->getNumber();
            $tablica['zdjecie'] = $pet->getMainPic();
            $tablica['opis'] = $pet->getDescription();
            $data = $pet->getDateOfBirth();
            $tablica['data_urodzenia'] = $data->format('d-m-Y');
            $tablica['stan'] = $pet->getState();
            $tablica['id'] = $pet->getPetId();
            if($tablica['stan'] == 'adoptowany'){
                $adopted = $this->getDoctrine()->getRepository(Adopted::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $adopted->getDateOfAdoption();
                $tablica['data_ad'] = $data->format('d-m-Y');
            }
            elseif($tablica['stan'] == 'zmarł'){
                $dead = $this->getDoctrine()->getRepository(Dead::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $dead->getDateOfPassAway();
                $tablica['data_zm'] = $data->format('d-m-Y');
            }
            $zwierzak = $tablica;

            foreach ($photos as $p){
                $t['zdjecie'] = $p->getPhoto();
                $t['id'] = $p->getPhotoId();

                $zdjecia[] = $t;
            }

            return $this->render('showPet.html.twig', array(
                'zwierzak' => $zwierzak,
                'zdjecia' => $zdjecia,
                'typ' => $typ,
                'uprawnienia' => $uprawnienia,
                'ulubione' => $ulubione,
                'czyRezerwacja' => $czyRezerwacja,
                'zarezerwowany' => $zarezerwowany
            ));
        }
        elseif (!$photos && !$news){
            $tablica['imie'] = $pet->getName();
            $tablica['numer'] = $pet->getNumber();
            $tablica['zdjecie'] = $pet->getMainPic();
            $tablica['opis'] = $pet->getDescription();
            $data = $pet->getDateOfBirth();
            $tablica['data_urodzenia'] = $data->format('d-m-Y');
            $tablica['stan'] = $pet->getState();
            $tablica['id'] = $pet->getPetId();
            if($tablica['stan'] == 'adoptowany'){
                $adopted = $this->getDoctrine()->getRepository(Adopted::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $adopted->getDateOfAdoption();
                $tablica['data_ad'] = $data->format('d-m-Y');
            }
            elseif($tablica['stan'] == 'zmarł'){
                $dead = $this->getDoctrine()->getRepository(Dead::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $dead->getDateOfPassAway();
                $tablica['data_zm'] = $data->format('d-m-Y');
            }
            $zwierzak = $tablica;

            $dlug['kwota'] = $debt->getDebtValue();
            $dlug['splacone'] = $debt->getPaid();


            return $this->render('showPet.html.twig', array(
                'zwierzak' => $zwierzak,
                'typ' => $typ,
                'uprawnienia' => $uprawnienia,
                'ulubione' => $ulubione,
                'dlug' => $dlug,
                'czyRezerwacja' => $czyRezerwacja,
                'zarezerwowany' => $zarezerwowany
            ));
        }
        elseif (!$news){
            $tablica['imie'] = $pet->getName();
            $tablica['numer'] = $pet->getNumber();
            $tablica['zdjecie'] = $pet->getMainPic();
            $tablica['opis'] = $pet->getDescription();
            $data = $pet->getDateOfBirth();
            $tablica['data_urodzenia'] = $data->format('d-m-Y');
            $tablica['stan'] = $pet->getState();
            $tablica['id'] = $pet->getPetId();
            if($tablica['stan'] == 'adoptowany'){
                $adopted = $this->getDoctrine()->getRepository(Adopted::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $adopted->getDateOfAdoption();
                $tablica['data_ad'] = $data->format('d-m-Y');
            }
            elseif($tablica['stan'] == 'zmarł'){
                $dead = $this->getDoctrine()->getRepository(Dead::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $dead->getDateOfPassAway();
                $tablica['data_zm'] = $data->format('d-m-Y');
            }
            $zwierzak = $tablica;

            foreach ($photos as $p){
                $t['zdjecie'] = $p->getPhoto();
                $t['id'] = $p->getPhotoId();

                $zdjecia[] = $t;
            }

            $dlug['kwota'] = $debt->getDebtValue();
            $dlug['splacone'] = $debt->getPaid();

            return $this->render('showPet.html.twig', array(
                'zwierzak' => $zwierzak,
                'zdjecia' => $zdjecia,
                'typ' => $typ,
                'uprawnienia' => $uprawnienia,
                'ulubione' => $ulubione,
                'dlug' => $dlug,
                'czyRezerwacja' => $czyRezerwacja,
                'zarezerwowany' => $zarezerwowany
            ));
        }
        elseif (!$photos){
            $tablica['imie'] = $pet->getName();
            $tablica['numer'] = $pet->getNumber();
            $tablica['zdjecie'] = $pet->getMainPic();
            $tablica['opis'] = $pet->getDescription();
            $data = $pet->getDateOfBirth();
            $tablica['data_urodzenia'] = $data->format('d-m-Y');
            $tablica['stan'] = $pet->getState();
            $tablica['id'] = $pet->getPetId();
            if($tablica['stan'] == 'adoptowany'){
                $adopted = $this->getDoctrine()->getRepository(Adopted::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $adopted->getDateOfAdoption();
                $tablica['data_ad'] = $data->format('d-m-Y');
            }
            elseif($tablica['stan'] == 'zmarł'){
                $dead = $this->getDoctrine()->getRepository(Dead::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $dead->getDateOfPassAway();
                $tablica['data_zm'] = $data->format('d-m-Y');
            }
            $zwierzak= $tablica;

            foreach ($news as $n){
                $data = $n->getNewsDate();
                $tab['data_n'] = $data->format('d-m-Y');
                $tab['tytul'] = $n->getTitle();
                $tab['opis'] = $n->getDescription();
                $tab['id'] = $n->getNewsId();

                $dodatkowe[] = $tab;
            }

            $dlug['kwota'] = $debt->getDebtValue();
            $dlug['splacone'] = $debt->getPaid();

            return $this->render('showPet.html.twig', array(
                'zwierzak' => $zwierzak,
                'dodatkowe' => $dodatkowe,
                'typ' => $typ,
                'uprawnienia' => $uprawnienia,
                'ulubione' => $ulubione,
                'dlug' => $dlug,
                'czyRezerwacja' => $czyRezerwacja,
                'zarezerwowany' => $zarezerwowany
            ));
        }
        elseif (!$debt){
            $tablica['imie'] = $pet->getName();
            $tablica['numer'] = $pet->getNumber();
            $tablica['zdjecie'] = $pet->getMainPic();
            $tablica['opis'] = $pet->getDescription();
            $data = $pet->getDateOfBirth();
            $tablica['data_urodzenia'] = $data->format('d-m-Y');
            $tablica['stan'] = $pet->getState();
            $tablica['id'] = $pet->getPetId();
            if($tablica['stan'] == 'adoptowany'){
                $adopted = $this->getDoctrine()->getRepository(Adopted::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $adopted->getDateOfAdoption();
                $tablica['data_ad'] = $data->format('d-m-Y');
            }
            elseif($tablica['stan'] == 'zmarł'){
                $dead = $this->getDoctrine()->getRepository(Dead::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $dead->getDateOfPassAway();
                $tablica['data_zm'] = $data->format('d-m-Y');
            }
            $zwierzak = $tablica;

            foreach ($news as $n){
                $data = $n->getNewsDate();
                $tab['data_n'] = $data->format('d-m-Y');
                $tab['tytul'] = $n->getTitle();
                $tab['opis'] = $n->getDescription();
                $tab['id'] = $n->getNewsId();

                $dodatkowe[] = $tab;
            }

            foreach ($photos as $p){
                $t['zdjecie'] = $p->getPhoto();
                $t['id'] = $p->getPhotoId();

                $zdjecia[] = $t;
            }

            return $this->render('showPet.html.twig', array(
                'zwierzak' => $zwierzak,
                'dodatkowe' => $dodatkowe,
                'zdjecia' => $zdjecia,
                'typ' => $typ,
                'uprawnienia' => $uprawnienia,
                'ulubione' => $ulubione,
                'czyRezerwacja' => $czyRezerwacja,
                'zarezerwowany' => $zarezerwowany
            ));
        }
        else{
            $tablica['imie'] = $pet->getName();
            $tablica['numer'] = $pet->getNumber();
            $tablica['zdjecie'] = $pet->getMainPic();
            $tablica['opis'] = $pet->getDescription();
            $data = $pet->getDateOfBirth();
            $tablica['data_urodzenia'] = $data->format('d-m-Y');
            $tablica['stan'] = $pet->getState();
            $tablica['id'] = $pet->getPetId();
            if($tablica['stan'] == 'adoptowany'){
                $adopted = $this->getDoctrine()->getRepository(Adopted::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $adopted->getDateOfAdoption();
                $tablica['data_ad'] = $data->format('d-m-Y');
            }
            elseif($tablica['stan'] == 'zmarł'){
                $dead = $this->getDoctrine()->getRepository(Dead::class)->findOneBy(array(
                    'pet' => $id
                ));
                $data = $dead->getDateOfPassAway();
                $tablica['data_zm'] = $data->format('d-m-Y');
            }
            $zwierzak = $tablica;

            foreach ($news as $n){
                $data = $n->getNewsDate();
                $tab['data_n'] = $data->format('d-m-Y');
                $tab['tytul'] = $n->getTitle();
                $tab['opis'] = $n->getDescription();
                $tab['id'] = $n->getNewsId();

                $dodatkowe[] = $tab;
            }

            foreach ($photos as $p){
                $t['zdjecie'] = $p->getPhoto();
                $t['id'] = $p->getPhotoId();

                $zdjecia[] = $t;
            }

            $dlug['kwota'] = $debt->getDebtValue();
            $dlug['splacone'] = $debt->getPaid();

            return $this->render('showPet.html.twig', array(
                'zwierzak' => $zwierzak,
                'dodatkowe' => $dodatkowe,
                'zdjecia' => $zdjecia,
                'typ' => $typ,
                'uprawnienia' => $uprawnienia,
                'ulubione' => $ulubione,
                'dlug' => $dlug,
                'czyRezerwacja' => $czyRezerwacja,
                'zarezerwowany' => $zarezerwowany
            ));
        }
    }
}