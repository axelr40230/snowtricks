<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use App\Form\RegistrationType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, ValidatorInterface $validator, UserPasswordHasherInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            
            $entityManager = $this->getDoctrine()->getManager();

            $plainPassword = $user->getPassword();

            $hash = $encoder->hashPassword($user, $plainPassword);

            $user->setPassword($hash);

            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('security/registration.html.twig', [
            'errors' => $form->getErrors(),
            'form' => $form->createView(),
            'title' => 'S\'inscrire'
        ]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(Request $request, UserRepository $repo, UserPasswordHasherInterface $encoder)
    {
        $user = new User();

        $form = $this->createForm(LoginType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $user = $repo->findOneBy(["email"=> $form->getData()->getEmail()]);




        }

        return $this->render('security/login.html.twig', [
            'errors' => $form->getErrors(),
            'form' => $form->createView(),
            'title' => 'Se connecter'
        ]);
    }
}
