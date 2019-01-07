<?php
/**
 * Created by PhpStorm.
 * User: Marta
 * Date: 13.12.2018
 * Time: 11:27
 */

namespace App\Controller;

use App\Entity\Pets;
use App\Entity\Photos;
use App\Form\Type\PhotosType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PhotosController extends AbstractController
{
    /**
     * @param Pets $id
     * @Route("/addphoto/{id}", requirements={"id" = "\d+"}, name="dodaj_zdjecia")
     * @return RedirectResponse
     */
    public function addPhoto(Request $request, Pets $id):Response{
        //$id=13;
        $form = $this->createForm(PhotosType::class);
        $photos = new Photos();
        $entityManager = $this->getDoctrine()->getManager();
        $form->handleRequest($request);

        try{
            if($form->isValid() && $form->isSubmitted()) {
                $file = $form->get('zdjecie')->getData();
                $nazwa = $file->getClientOriginalName();
                $nazwa = explode('.', $nazwa);
                $dlugosc = sizeof($nazwa);
                $fileName = $this->generateUniqueFileName() . '.' . $nazwa[$dlugosc - 1];
                $file->move(
                    $this->getParameter('animals_photos'),
                    $fileName
                );
                $photos->setPhoto($fileName);
                $photos->setPet($id);
                $entityManager->persist($photos);
                $entityManager->flush();

                $this->addFlash('success', 'photos_add_successfully');

                return $this->redirectToRoute('wyswietl_zwierzaka', array(
                    'id' => $id->getPetId()
                ));
            }
        }
        catch(\Exception $e){
            $this->addFlash("error", "photos.add_photos");
        }

        return $this->render('addPhotos.html.twig', array(
            'form' =>$form->createView(),
            'id' => $id->getPetId()
        ));

    }

    private function generateUniqueFileName(){
        return md5(uniqid());
    }

    /**
     * @param Photos $id
     * @Route("/removepic/{id}", requirements={"id" = "\d+"}, name="usun_zdjecie")
     * @return RedirectResponse
     */
    public function removePhoto(Photos $id){
        //$id=13;
        $photos = $this->getDoctrine()->getRepository(Photos::class)->find($id);
        $pet = $this->getDoctrine()->getRepository(Pets::class)->find($id->getPet());
        $entityManager = $this->getDoctrine()->getManager();
        $filesystem = new Filesystem();
        try{
            $filesystem->remove($photos->getPhoto());
            $entityManager->remove($photos);
            $entityManager->flush();
            $this->addFlash('success', 'photo.removed_successfully');

        }catch(\Exception $e)
        {
            $this->addFlash('error', $e);
        }

        return $this->redirectToRoute('wyswietl_zwierzaka', array(
            'id' => $pet->getPetId()
        ));
    }


    /**
     * @param Photos $pic
     * @Route("changemainpic/{pic}", requirements={"pic" = "\d+"}, name="zmienglownezdjecie")
     */
    public function changeMainPhoto(Photos $pic){
        $photos = $this->getDoctrine()->getRepository(Photos::class)->find($pic->getPhotoId());
        $pet = $this->getDoctrine()->getRepository(Pets::class)->find($photos->getPet());
        $filesystem = new Filesystem();
        $entityManager = $this->getDoctrine()->getManager();
        $file = $photos->getPhoto();
        $file2 = $pet->getMainPic();

        $pet->setMainPic($file);
        $entityManager->flush();

        $photos->setPhoto($file2);
        $entityManager->flush();


        return $this->redirectToRoute('wyswietl_zwierzaka', array(
            "id" => $pet->getPetId()
        ));
    }

}