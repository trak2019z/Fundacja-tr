<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 05.12.2018
 * Time: 22:48
 */

namespace App\Controller;


use App\Entity\Logging;
use App\Entity\MyUser;
use App\Entity\Permissions;
use App\Entity\Workers;
use App\Form\Type\AddWorkerType;
use App\Form\Type\ChangePermissionType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class WorkerController extends AbstractController
{
    /**
     * @Route("/add", methods={"GET","POST"}, name="dodaniePracownikaFormularz")
     */
    public function addWorker(Request $request, UserPasswordEncoderInterface $passwordEncoder) :Response {
        $logging = new MyUser();
        $worker = new Workers();
        $permission = new Permissions();
        $form = $this->createForm(AddWorkerType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $entityManager = $this->getDoctrine()->getManager();

            $email = $form->get('daneLogowania')->get('emailUzytkownika')->getData();
            $haslo1 = $form->get('daneLogowania')->get('hasloUzytkownika')->get('first')->getData();
            $haslo2 = $form->get('daneLogowania')->get('hasloUzytkownika')->get('second')->getData();

            if($haslo1 === $haslo2)
            {
                $logging->setEmail($email);
                $logging->setPassword($passwordEncoder->encodePassword(
                    $logging,
                    $haslo1
                ));
                $logging->setTypeOfAccount('worker');
                $entityManager->persist($logging);
                $entityManager->flush();

                $worker->setName($form->get('imiePracownika')->getData());
                $worker->setSurname($form->get('nazwiskoPracownika')->getData());
                $data = new \DateTime($form->get('dataUrodzenia')->getData());
                $worker->setDateOfBirth($data);
                $worker->setPosition($form->get('pozycjaPracownika')->getData());
                $worker->setUserId($logging->getId());
                $entityManager->persist($worker);
                $entityManager->flush();

                if($form->get('checkboxPracownik')->get('checkDodajZwierze')->getData() == 1)
                    $permission->setAddPets(
                        true);
                else
                    $permission->setAddPets(
                        false);

                if($form->get('checkboxPracownik')->get('checkEdycjaZwierzat')->getData() == 1)
                    $permission->setEditPets(
                        true);
                else
                    $permission->setEditPets(
                        false);

                if($form->get('checkboxPracownik')->get('checkUsunZwierze')->getData() == 1)
                    $permission->setDeletePets(
                        true);
                else
                    $permission->setDeletePets(
                        false);

                if($form->get('checkboxPracownik')->get('checkPrzeniesZwierze')->getData() == 1)
                    $permission->setMovePets(
                        true);
                else
                    $permission->setMovePets(
                        false);

                if($form->get('checkboxPracownik')->get('checkDodajDlug')->getData() == 1)
                    $permission->setAddDebt(
                        true);
                else
                    $permission->setAddDebt(
                        false);

                if($form->get('checkboxPracownik')->get('checkEdycjaDlugow')->getData() == 1)
                    $permission->setChangeDebt(
                        true);
                else
                    $permission->setChangeDebt(
                        false);

                if($form->get('checkboxPracownik')->get('checkAkceptujRezerwacje')->getData() == 1)
                    $permission->setAcceptReservation(
                        true);
                else
                    $permission->setAcceptReservation(
                        false);
                $permission->setWorker($worker);
                $entityManager->persist($permission);
                $entityManager->flush();
            }

            $this->addFlash('success', 'user.created_successfully');

            return $this->redirectToRoute('dodaniePracownikaFormularz');
        }

        return $this->render('addWorker.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/showworkers", name="wszyscyPracownicy")
     */
    public function showWorkers(){
        $workers = $this->getDoctrine()->getRepository(Workers::class)->findAll();
        $tablica_pracownikow = array();
        foreach ($workers as $worker){
            $pracownik_dane['imie'] = $worker->getName();
            $pracownik_dane['nazwisko'] = $worker->getSurname();
            $pozycja = $worker->getPosition();
            if($pozycja === "kierownik")
                $pracownik_dane['pozycja'] = 'Kierownik';
            elseif ($pozycja === "zastepca")
                $pracownik_dane['pozycja'] = 'Zastępca kierownika';
            elseif ($pozycja === "pracownikS")
                $pracownik_dane['pozycja'] = 'Starszy pracownik';
            elseif ($pozycja === "pracownik")
                $pracownik_dane['pozycja'] = 'Pracownik';
            elseif ($pozycja === "stazysta")
                $pracownik_dane['pozycja'] = 'Stażysta';
            elseif ($pozycja === "wolontariusz")
                $pracownik_dane['pozycja'] = 'Wolontariusz';

            $pracownik_dane['id'] = $worker->getWorkerId();

            $tablica_pracownikow[] = $pracownik_dane;
        }

        return $this->render('showWorkers.html.twig', array(
            "pracownicy" => $tablica_pracownikow
        ));
    }

    /**
     * @param Workers $id
     * @Route("/{id}/removew", requirements={"id" = "\d+"}, name="usunPracownika")
     * @return RedirectResponse
     */
    public function removeWorker(Workers $id){
        $worker = $this->getDoctrine()->getRepository(Workers::class)->find($id->getWorkerId());
        $permissions = $this->getDoctrine()->getRepository(Permissions::class)->findOneBy(array(
            "worker" => $id->getWorkerId()
        ));
        $logging = $this->getDoctrine()->getRepository(MyUser::class)->find($id->getUserId());
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($permissions);
        $entityManager->flush();
        $entityManager->remove($worker);
        $entityManager->flush();
        $entityManager->remove($logging);
        $entityManager->flush();

        return $this->redirectToRoute('wszyscyPracownicy');
    }

    /**
     * @param Workers $id
     * @Route("/{id}/changepermission", requirements={"id" = "\d+"}, name="zmianaUprawnien")
     * @return RedirectResponse
     */
    public function changePermissions(Request $request, Workers $id){
        $form = $this->createForm(ChangePermissionType::class);
        $permissions = $this->getDoctrine()->getRepository(Permissions::class)->findOneBy(array(
            "worker" => $id->getWorkerId()
        ));

        if(!$permissions){
            throw $this->createNotFoundException(
                'No permission found for worker id '.$id->getWorkerId()
            );
        }
        else{
            $form->get("checkbox")->get('checkDodajZwierze')->setData($permissions->getAddPets());
            $form->get("checkbox")->get('checkEdycjaZwierzat')->setData($permissions->getEditPets());
            $form->get("checkbox")->get('checkUsunZwierze')->setData($permissions->getDeletePets());
            $form->get("checkbox")->get('checkPrzeniesZwierze')->setData($permissions->getMovePets());
            $form->get("checkbox")->get('checkDodajDlug')->setData($permissions->getAddDebt());
            $form->get("checkbox")->get('checkEdycjaDlugow')->setData($permissions->getChangeDebt());
            $form->get("checkbox")->get('checkAkceptujRezerwacje')->setData($permissions->getAcceptReservation());
        }

        $form->handleRequest($request);

        try{
            if($form->isSubmitted() && $form->isValid()){
                $entityManager = $this->getDoctrine()->getManager();
                $permissions->setAddPets($form->get("checkbox")->get('checkDodajZwierze')->getData());
                $permissions->setEditPets($form->get("checkbox")->get('checkEdycjaZwierzat')->getData());
                $permissions->setDeletePets($form->get("checkbox")->get('checkUsunZwierze')->getData());
                $permissions->setMovePets($form->get("checkbox")->get('checkPrzeniesZwierze')->getData());
                $permissions->setAddDebt($form->get("checkbox")->get('checkDodajDlug')->getData());
                $permissions->setChangeDebt($form->get("checkbox")->get('checkEdycjaDlugow')->getData());
                $permissions->setAcceptReservation($form->get("checkbox")->get('checkAkceptujRezerwacje')->getData());
                $entityManager->flush();

                return $this->redirectToRoute('wszyscyPracownicy');
            }

        }catch(\Exception $e){
            $this->addFlash("blad przy zmianie upranwien", $e);
        }

        return $this->render('changePermissions.html.twig', array(
            "form" => $form->createView()
        ));

    }

}
