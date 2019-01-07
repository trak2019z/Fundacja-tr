<?php

namespace App\Controller;

use App\Entity\Guests;
use App\Entity\MyUser;
use App\Entity\Workers;
use App\Form\Type\ChangePasswordType;
use App\Form\Type\EditUserType;
use App\Security\LoginFormAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="logowanie")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error'         => $error,
        ]);
    }

    /**
     * @Route("/changepass", name="zmienHasloUzytkownika")
     */
    public function changePassword(Request $request, UserPasswordEncoderInterface $passwordEncoder){
        $form = $this->createForm(ChangePasswordType::class);
        $logging = $this->getDoctrine()->getRepository(MyUser::class)->findOneBy(array(
            "email" => $this->getUser()->getEmail()
        ));
        $form->handleRequest($request);
        $entityManager = $this->getDoctrine()->getManager();

        if($form->isSubmitted() && $form->isValid()){
            try{
                $stareHaslo = $form->get("stareHaslo")->getData();
                if($passwordEncoder->isPasswordValid($logging, $stareHaslo)){
                    $haslo1 = $form->get("hasloUzytkownika")->get("first")->getData();
                    $haslo2 = $form->get("hasloUzytkownika")->get("second")->getData();
                    if($haslo1 === $haslo2){

                        $logging->setPassword($passwordEncoder->encodePassword(
                            $logging,
                            $haslo1
                        ));
                        $entityManager->flush();
                    }
                    return $this->redirectToRoute("account");
                }
                else{
                    return $this->redirectToRoute('zmienHasloUzytkownika');
                }



            }catch (\Exception $e){
                $this->addFlash("blad zmiana hasla", $e);
            }

        }



        return $this->render("changePassword.html.twig", array(
            "form" => $form->createView()
        ));

    }

    /**
     * @Route("/editdata", name="editUserData")
     *
     */
    public function editData(Request $request){
        $logging = $this->getDoctrine()->getRepository(MyUser::class)->findOneBy(array(
            "email" => $this->getUser()->getEmail()
        ));
        $entityManager = $this->getDoctrine()->getManager();
        $typ = $logging->getTypeOfAccount();
        $form = $this->createForm(EditUserType::class);

        if($typ === "guest"){
            $guest = $this->getDoctrine()->getRepository(Guests::class)->findOneBy(array(
                "userId" => $logging->getId()
            ));
            $form->get('imie')->setData($guest->getName());
            $form->get('nazwisko')->setData($guest->getName());
        }
        elseif ($typ == "worker"){
            $worker = $this->getDoctrine()->getRepository(Workers::class)->findOneBy(array(
                "userId" => $logging->getId()
            ));
            $form->get('imie')->setData($worker->getName());
            $form->get('nazwisko')->setData($worker->getSurname());
        }

        $form->handleRequest($request);

        try{
            if($form->isSubmitted() && $form->isValid()){
                if($typ === "guest"){
                    $guest->setName($form->get('imie')->getData());
                    $guest->setSurname($form->get('nazwisko')->getData());
                    $entityManager->flush();
                }
                else if($typ === "worker"){
                    $worker->setName($form->get('imie')->getData());
                    $worker->setSurname($form->get('nazwisko')->getData());
                    $entityManager->flush();
                }
                return $this->redirectToRoute('myAccountInfo', array(
                ));
            }
        }catch(\Exception $e){
            $this->addFlash("blad edycja danych", $e);
        }
        return $this->render('editUserData.html.twig', array(
            "form" => $form->createView()
        ));
    }


    /**
     * @Route("myaccount", name="myAccountInfo")
     */
    public function myAccount(){
        $logging = $this->getDoctrine()->getRepository(MyUser::class)->findOneBy(array(
            "email" => $this->getUser()->getEmail()
        ));
        $type = $logging->getTypeOfAccount();

        if($type === "guest")
        {
            $user = $this->getDoctrine()->getRepository(Guests::class)->findOneBy(array(
                "userId" => $logging->getId()
            ));
            $tablica['imie'] = $user->getName();
            $tablica['nazwisko'] = $user->getSurname();
        }
        else if($type === "worker"){
            $worker = $this->getDoctrine()->getRepository(Workers::class)->findOneBy(array(
                "userId" => $logging->getId()
            ));
            $tablica['imie'] = $worker->getName();
            $tablica['nazwisko'] = $worker->getSurname();
            $data = $worker->getDateOfBirth();
            $tablica['data'] = $data->format('d-m-Y');
            $pozycja = $worker->getPosition();
            if($pozycja === "kierownik")
                $tablica['pozycja'] = 'Kierownik';
            elseif ($pozycja === "zastepca")
                $tablica['pozycja'] = 'Zastępca kierownika';
            elseif ($pozycja === 'pracownikS')
                $tablica['pozycja'] = 'Starszy pracownik';
            elseif ($pozycja === 'pracownik')
                $tablica['pozycja'] = 'Pracownik';
            elseif ($pozycja === 'stazysta')
                $tablica['pozycja'] = 'Stażysta';
            else
                $tablica['pozycja'] = 'wolontariusz';
        }

        $tablica['email'] = $logging->getEmail();
        return $this->render('showUserData.html.twig', array(
            "tablica" => $tablica,
            "typ" => $type
        ));
    }

    /**
     * @Route("/logout", name="wyloguj")
     */
    public function logout(){
        throw new \Exception("Wylogowywanie");
    }

    /**
     * @Route("/register", name="rejestracja2")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder, GuardAuthenticatorHandler $guardHandler, LoginFormAuthenticator $formAuthenticator){
        if ($request->isMethod('POST')) {
            $user = new MyUser();
            $user->setEmail($request->request->get('email'));
            $user->setTypeOfAccount('guest');
            $haslo1 = $request->request->get('password');
            $haslo2 = $request->request->get('password2');
            if($haslo1 === $haslo2){
                try{
                    $user->setPassword($passwordEncoder->encodePassword(
                        $user,
                        $request->request->get('password')
                    ));

                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();

                    $guest = new Guests();
                    $guest->setName($request->request->get('name'));
                    $guest->setSurname($request->request->get('surname'));
                    $guest->setUserId($user->getId());

                    $entityManager->persist($guest);
                    $entityManager->flush();

                    return $guardHandler->authenticateUserAndHandleSuccess(
                        $user,
                        $request,
                        $formAuthenticator,
                        'main'
                    );

                }catch (\Exception $e){
                    $this->addFlash("problem z dodaniem uzytkownika", $e);
                }
            }
            else{
                $this->addFlash("error", "Hasła nie są zgodne");
                return $this->render('security/register.html.twig');
            }

        }
        return $this->render('security/register.html.twig');
    }


    /**
     * @Route("/removeaccount", name="usunkonto")
     */
    public function removeAccount(){
        $logging = $this->getDoctrine()->getRepository(MyUser::class)->findOneBy(array(
            "email" => $this->getUser()->getEmail()
        ));
        $guest = $this->getDoctrine()->getRepository(Guests::class)->findOneBy(array(
            "userId" => $logging->getId()
        ));
        try{
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($guest);
            $entityManager->flush();
            $entityManager->remove($logging);
            $entityManager->flush();

        }
        catch(\Exception $e){
            $this->addFlash("nie udało się usunąć konta" , $e);
        }

        return $this->redirect("http://mjaw.ayz.pl/");
    }
}
