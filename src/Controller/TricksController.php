<?php

namespace App\Controller;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TricksController extends AbstractController
{
    /**
     * @Route("/tricks", name="tricks")
     */
    public function index(TrickRepository $repo): Response
    {
        $tricks = $repo->findAll();

        return $this->render('tricks/index.html.twig', [
            'controller_name' => 'TricksController',
            'tricks' => $tricks,
            'title' => "All the snowboarding tricks"
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(TrickRepository $repo): Response
    {
        $tricks = $repo->findBy(array(),null,12);

        return $this->render('tricks/home.html.twig', [
            'controller_name' => 'TricksController',
            'tricks' => $tricks,
            'title' => "Share all the snowboarding tricks to master"
        ]);
    }

    /**
     * @Route("/trick/new", name="trick_create")
     * @Route("/trick/{slug}/edit", name="trick_edit")
     */
    public function form(Trick $trick = null, Request $request, ValidatorInterface $validator)
    {

        if(!$trick) {
            $trick = new Trick();
        }

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            if(!$trick->getId()) {
                $trick->setCreatedAt(new \DateTime());
                $trick->setModifyAt(new \DateTime());
            }
            $trick->setModifyAt(new \DateTime());

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('tricks_show',['slug' => $trick->getSlug()]);
        }

        return $this->render('tricks/create.html.twig', [
            'errors' => $form->getErrors(),
            'formTrick' => $form->createView(),
            'title' => $trick->getTitle() !== null,
            'editMode' => $trick->getId() !== null,
            'trick' => $trick
        ]);
    }

    /**
     * @Route("/tricks/{slug}", name="tricks_show")
     */
    public function show(Trick $trick)
    {
        return $this->render('tricks/show.html.twig', [
            'trick' => $trick,
            'title' => $trick->getTitle()
        ]);
    }
}
