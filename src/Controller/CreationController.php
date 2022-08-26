<?php

namespace App\Controller;



use App\Entity\Generateur;
use App\Form\GenerateurType;
use App\Repository\GenerateurRepository;
use App\Service\GenerateurService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;



class CreationController extends AbstractController
{
    /**
     * @Route("/ajout", name="ajout_creation")
     * @param EntityManagerInterface $entityManager
     * @param Request $request
     * @param GenerateurRepository $repository
     * @return Response
     */
    public function ajout(EntityManagerInterface $entityManager, Request $request, GenerateurRepository $repository, GenerateurService $generateurService): Response
    {
        //on  crée un générateur
        $creation = new Generateur();
        //on creer le form
        $creationForm = $this->createForm(GenerateurType::class, $creation);

        //traitement des données du formulaire
        $creationForm->handleRequest($request);

        //si le formulaire est soumis et qu'il est valide
        if ($creationForm->isSubmitted() && $creationForm->isValid()) {


            $nomAddOn = $creationForm->get('nomAddOn')->getData();

            //fichier InterfaceScreen.html
            $html = $this->renderView('creation/interfaceScreen.html.twig', [
                'nomAddOn' => $nomAddOn,
            ]);

            $crudListHtml = $this->renderView('creation/crudList.html.twig', [

            ]);

            $search = $this->renderView('creation/search.html.twig', [

            ]);
            $generateurService->generate($creationForm, $entityManager, $creation, $html,$crudListHtml,$search);


        }
            //creationForm est envoyer au fichier index.html.twig
            return $this->render('creation/index.html.twig', [

                'creationForm' => $creationForm->createView(),


            ]);

    }

}
