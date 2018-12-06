<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 05.12.2018
 * Time: 22:48
 */

namespace App\Controller;


use App\Entity\Logging;
use App\Entity\Workers;
use App\Form\Type\AddWorkerType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddWorkerController extends AbstractController
{
    /**
     * @Route("/add", methods={"GET","POST"}, name="dodaniePracownikaFormularz")
     */
    public function addWorker(Request $request) :Response {
        $logging = new Logging();
        $worker = new Workers();
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
            }

            $this->addFlash('success', 'user.updated_successfully');

            return $this->redirectToRoute('dodaniePracownikaFormularz');
        }

        return $this->render('addWorker.html.twig', array(
            'form2' => $form->createView()
        ));
    }

}
