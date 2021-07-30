<?php

namespace App\Controller;

use App\Entity\Frequency;
use App\Form\FrequencyType;
use App\Repository\FrequencyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class FrequencyController extends AbstractController
{
    /**
     * @Route("/gestion_frequency", name="frequency_afficher")
     */
    public function frequency_afficher(FrequencyRepository $repoFrequency): Response
    {
        return $this->render('frequency/frequency_afficher.html.twig', [
            "frequencies" => $repoFrequency->findAll()
        ]);
    }

    /**
     * @Route("/gestion_frequency/ajouter", name="frequency_ajouter")
     * @Route("/gestion_frequency/modifier/{id}", name="frequency_modifier")
     */
    public function frequency_ajouter_modifier(frequency $frequency = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$frequency)
        {
            $frequency = new Frequency;
        }
        
        $form = $this->createForm(FrequencyType::class, $frequency);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid())
        {
            $modif = $frequency->getId() !== null;
            $manager->persist($frequency); 
            $manager->flush(); 

            $this->addFlash('success', ($modif) ? "La frequency " . $frequency->getName() ." a bien été modifiée" : "La frequence " . $frequency->getName() ." a bien été ajoutée");

            return $this->redirectToRoute("frequency_afficher");


        }
        

        return $this->render('frequency/frequency_ajouter_modifier.html.twig', [
            "formFrequency" => $form->createView(), 
            "frequency" => $frequency,
            "modification" => $frequency->getId() !== null
        ]);
    }

    /**
     * @Route("/gestion_frequency/supprimer/{id}", name="frequency_supprimer")
     */
    public function frequency_supprimer(Frequency $frequency, EntityManagerInterface $manager)
    {

        $nomFrequency = $frequency->getName();
        $idFrequency = $frequency->getId();

        $manager->remove($frequency);
        $manager->flush();


        $this->addFlash('success', "La fréquence $nomFrequency a bien été supprimée");

        return $this->redirectToRoute("frequency_afficher");


    }
}