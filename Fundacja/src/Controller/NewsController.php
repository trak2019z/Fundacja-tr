<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 12.12.2018
 * Time: 17:39
 */

namespace App\Controller;


use App\Entity\Additionals;
use App\Entity\Pets;
use App\Form\Type\NewsType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

class NewsController extends AbstractController
{
    /**
     * @param Pets $id
     *
     * @Route("/news/{id}", requirements={"id" = "\d+"}, name="dodawanie_wpisu")
     * @return RedirectResponse
     */
    public function addNews(Request $request, Pets $id){
        $news = new Additionals();
        $form = $this->createForm(NewsType::class);
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if($form->isSubmitted() && $form->isValid()) {
            if ($form->get('tytul')->getData() != '')
                $news->setTitle($form->get('tytul')->getData());
            $news->setDescription($form->get('opis')->getData());
            $news->setNewsDate(new \DateTime());
            $news->setPet($id);

            $entityManager->persist($news);
            $entityManager->flush();

            $this->addFlash('success', 'news.created_successfully');
            return $this->redirectToRoute('wyswietl_zwierzaka', array(
                'id' => $id->getPetId()
            ));
        }

        return $this->render('addNews.html.twig', array(
            'form' =>$form->createView(),
            'id' => $id->getPetId()
        ));
    }

    /**
     * @param Additionals $id
     * @Route("/newsedit/{id}", requirements={"id" = "\d+"}, name="edycja_wpisu")
     * @return RedirectResponse
     */
    public function editNews(Request $request, Additionals $id){
        $form = $this->createForm(NewsType::class);
        $news = $this->getDoctrine()->getRepository(Additionals::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();

        $pet = new Pets();
        $pet = $this->getDoctrine()->getRepository(Pets::class)->find($id->getPet());

        $form->get('tytul')->setData($news->getTitle());
        $form->get('opis')->setData($news->getDescription());
        $form->handleRequest($request);

        try{
            if($form->isSubmitted() && $form->isValid()){
                $news->setTitle($form->get('tytul')->getData());
                $news->setDescription($form->get('opis')->getData());

                $entityManager->flush();

                $this->addFlash('success', 'news.edited_successfully');
                return $this->redirectToRoute('wyswietl_zwierzaka', array(
                    'id' => $pet->getPetId()
                ));
            }
        }catch (\Exception $e){
            $this->addFlash('error', $e);
        }

        return $this->render('addNews.html.twig', array(
            'form' => $form->createView(),
            'id' => $pet->getPetId()
        ));

    }

    /**
     * @param Additionals $id
     * @Route("/removenews/{id}", requirements={"id" = "\d+"}, name="usuwanie_newsow")
     * @return RedirectResponse
     */
    public function removeNews(Additionals $id){
        $news = $this->getDoctrine()->getRepository(Additionals::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();

        $pet = $this->getDoctrine()->getRepository(Pets::class)->find($news->getPet());

        try{
            $entityManager->remove($news);
            $entityManager->flush();
            $this->addFlash('success', 'news.removed_successfully');
        }catch (\Exception $e){
            $this->addFlash('error', $e);
        }

        return $this->redirectToRoute('wyswietl_zwierzaka', array(
            'id' => $pet->getPetId()
        ));
    }
}