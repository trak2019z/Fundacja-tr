<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 05.12.2018
 * Time: 22:48
 */

namespace App\Controller;


use App\Entity\Logging;
use App\Entity\Permissions;
use App\Entity\Workers;
use App\Form\Type\AddWorkerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WorkerController extends AbstractController
{
    /**
     * @Route("/add", methods={"GET","POST"}, name="dodaniePracownikaFormularz")
     */
    public function addWorker(Request $request) :Response {
        $logging = new Logging();
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
                $logging->setPassword($haslo1);
                $logging->setTypeOfAccount('worker');
                $entityManager->persist($logging);
                $entityManager->flush();

                $worker->setName($form->get('imiePracownika')->getData());
                $worker->setSurname($form->get('nazwiskoPracownika')->getData());
                $worker->setDateOfBirth($form->get('dataUrodzenia')->getData());
                $worker->setPosition($form->get('pozycjaPracownika')->getData());
                $worker->setLogging($logging);
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



}
