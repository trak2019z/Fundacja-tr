<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 12.12.2018
 * Time: 18:21
 */

namespace App\Controller;


use App\Entity\Debts;
use App\Entity\Pets;
use App\Form\Type\ChangeDebtType;
use App\Form\Type\DebtType;
use PHPUnit\Runner\Exception;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class DebtController extends AbstractController
{
    /**
     * @param Pets $pet
     * @Route("/{pet}/adddebt", requirements={"pet" = "\d+"}, methods={"GET","POST"}, name="dodawanie_dlugu")
     * @return RedirectResponse
     */
    public function addDebt(Request $request, Pets $pet){
        $id = $pet->getPetId();
        $form = $this->createForm(DebtType::class);
        $debt = new Debts();
        $entityManager = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        try{
            if($form->isSubmitted() && $form->isValid()){
                $debt->setPet($pet);
                $debt->setDebtValue($form->get('kwota')->getData());
                $debt->setPaid(0);

                $entityManager->persist($debt);
                $entityManager->flush();

                $this->addFlash('success', 'debt.created_successfully');
                return $this->redirectToRoute('wyswietl_zwierzaka', array(
                    'id' => $id
                ));
            }
        }catch (\Exception $e){
            $this->addFlash('error', $e);
        }

        return $this->render('addDebt.html.twig', array(
            'form' => $form->createView(),
            'id' => $id
        ));
    }

    /**
     * @param Pets $pet
     * @Route("{pet}/editdebt", requirements = {"pet" = "\d+"} , name="edycja_dlugu")
     * @return RedirectResponse
     */
    public function  editDebt(Request $request, Pets $pet){
        $form = $this->createForm(ChangeDebtType::class);
        $debt = $this->getDoctrine()->getRepository(Debts::class)->findOneBy(array(
            "pet" => $pet->getPetId()
        ));

        $form->handleRequest($request);
        try{
            if($form->isSubmitted() && $form->isValid()){
                $entityManager = $this->getDoctrine()->getManager();
                if($form->get('zwiekszenieDlugu')->getData() > 0){
                    $wartosc = $debt->getDebtValue() + $form->get('zwiekszenieDlugu')->getData();
                    $debt->setDebtValue($wartosc);
                }
                else
                if($form->get('zmniejszenieDlugu')->getData() > 0){
                    $wartosc = $debt->getPaid() + $form->get('zmniejszenieDlugu')->getData();
                    $debt->setPaid($wartosc);
                }

                $entityManager->flush();

                $this->addFlash('success', 'pet.edited_successfully');
                return $this->redirectToRoute('wyswietl_zwierzaka', array(
                    'id' => $pet->getPetId()
                ));

            }
        }catch(Exception $e){
            $this->addFlash('error', $e);
        }

        return $this->render('editDebt.html.twig', array(
            'form' => $form->createView(),
            'id' => $pet->getPetId()
        ));
    }

    /**
     * @param Pets $pet
     * @Route("{pet}/removedebt", requirements={"pet" = "\d+"} ,name="usuwanie_dlugu")
     * @return RedirectResponse
     */
    public function removeDebt(Pets $pet){
        $dlug = $this->getDoctrine()->getRepository(Debts::class)->findOneBy(array(
            "pet" => $pet->getPetId()
        ));
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($dlug);
        $entityManager->flush();

        return $this->redirectToRoute('wyswietl_zwierzaka', array(
            "id" => $pet->getPetId()
        ));
    }

}