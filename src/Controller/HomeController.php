<?php

namespace App\Controller;

use App\Entity\Pseudo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use App\Form\PseudoFormType;
use App\Repository;

class HomeController extends AbstractController {

    /**
     * @Route("/", name="home")
     */
    public function index(Request $request) {
        # Creating a nickname    
        $nouveauPseudo = new Pseudo();

        #créating a form
        $form = $this->createForm(PseudoFormType::class, $nouveauPseudo)
                ->handleRequest($request);

        # If the form is submitted and valid
        if ($form->isSubmitted() && $form->isValid()) {

            # Backup to database
            $em = $this->getDoctrine()->getManager();
            $em->persist($nouveauPseudo);
            $em->flush();
        }



        return $this->render('home/home.html.twig', [
                    'controller_name' => 'HomeController',
                    'nouveauPseudo' => $form->createView(),
        ]);
    }

    /**
     * @Route("/pseudo/ajax", name="ajax") 
     */
    
    public function ajaxAction() {

        $listPseudos = $this->getDoctrine()->getRepository(\App\Entity\Pseudo::class)->findByPseudo();

        $myJsonResponse = new JsonResponse($listPseudos);

        return $myJsonResponse;
    }

}
