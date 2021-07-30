<?php

namespace App\Controller;

use App\Entity\Communication;
use App\Form\CommunicationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CommunicationRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class CommunicationController extends AbstractController
{
    /**
     * @Route("/gestion_communication", name="communication_afficher")
     */
    public function communication_afficher(CommunicationRepository $repoCommunication): Response
    {
        return $this->render('communication/communication_afficher.html.twig', [
            'communications' => $repoCommunication->findAll(),
        ]);
    }

    /**
     * @Route("/gestion_communication/ajouter", name="communication_ajouter")
     * @Route("/gestion_communication/modifier/{id}", name="communication_modifier")
     */
    public function communication_ajouter_modifier(Communication $communication = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$communication)
        {
            $communication = new Communication;
        }
        
        $form = $this->createForm(CommunicationType::class, $communication);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid())
        {
            $modif = $communication->getId() !== null;
            $manager->persist($communication); 
            $manager->flush(); 

            $this->addFlash('success', ($modif) ? "La communication " . $communication->getName() ." a bien été modifiée" : "La communication " . $communication->getName() ." a bien été ajoutée");

            return $this->redirectToRoute("communication_afficher");


        }
        

        return $this->render('communication/communication_ajouter_modifier.html.twig', [
            "formCommunication" => $form->createView(), 
            "communication" => $communication,
            "modification" => $communication->getId() !== null
        ]);
    }

     /**
     * @Route("/gestion_communication/supprimer/{id}", name="communication_supprimer")
     */
    public function communication_supprimer(Communication $communication, EntityManagerInterface $manager)
    {



        $nomCommunication = $communication->getName();
        $idCommunication = $communication->getId();

        $manager->remove($communication);
        $manager->flush();


        $this->addFlash('success', "La disponibilité $nomCommunication a bien été supprimée");

        return $this->redirectToRoute("communication_afficher");


    }



}
