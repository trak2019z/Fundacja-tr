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
use App\Form\Type\DebtType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class DebtController extends AbstractController
{
    /**
     * @Route("/adddebt", name="dodawanie_dlugu")
     */
    public function addDebt(Request $request){
        $pet = new Pets();
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
                return $this->redirectToRoute('wyswietl_zwierzaki');
            }
        }catch (\Exception $e){
            $this->addFlash('error', $e);
        }

        return $this->render('addDebt.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/editdebt", name="edycja_dlugu")
     */
    public function  editDebt(Request $request){

    }

    /**
     * @Route("/removedebt", name="usuwanie_dlugu")
     */
    public function removeDebt($id){

    }

    /**
     * @Route("debt", name="wyswietlanie_dlugu")
     */
    public function showDebt($id){
        $debt = $this->getDoctrine()->getRepository(Debts::class)->findOneBy(array(
            'pet' => $id
        ));

        $dlug = array();
        $dlug['kwota'] = $debt->getDebtValue();
        $dlug['splacone'] = $debt->getPaid();

        return $this->render('showDebt.html.twig', array(
            'dlug' => $dlug
        ));

    }

}