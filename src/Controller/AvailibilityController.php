<?php

namespace App\Controller;

use App\Entity\Availibility;
use App\Form\AvailibilityType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\AvailibilityRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


/**
 * @Route("/admin")
 */
class AvailibilityController extends AbstractController
{
    /**
     * La fonction availibility_afficher() permet d'afficher sous forme de tableau la liste des availibility (BACK OFFICE)
     * On y trouvera le bouton pour ajouter une availibility
     * Chaque ligne du tableau on trouvera les liens de modifier et de supprimer 
     * 
     * @Route("/gestion_availibility", name="availibility_afficher")
     */
    public function availibility_afficher(AvailibilityRepository $repoAvailibility)
    {
        return $this->render('availibility/availibility_afficher.html.twig', [
            "availibilities" => $repoAvailibility->findAll()
        ]);
    }


    

    /**
     * la fonction availibility_ajouter() permet d'ajouter une availibility 
     * Cette route se trouve sur la route availibility_afficher
     * 
     * @Route("/gestion_availibility/ajouter", name="availibility_ajouter")
     * @Route("/gestion_availibility/modifier/{id}", name="availibility_modifier")
     */
    public function availibility_ajouter_modifier(Availibility $availibility = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$availibility)
        {
            $availibility = new Availibility;
        }
        

        $form = $this->createForm(AvailibilityType::class, $availibility);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid())
        {
            $modif = $availibility->getId() !== null;
            $manager->persist($availibility); 
            $manager->flush(); 

            $this->addFlash('success', ($modif) ? "La disponibilité " . $availibility->getName() ." a bien été modifiée" : "La disponibilité " . $availibility->getName() ." a bien été ajoutée");

            return $this->redirectToRoute("availibility_afficher");


        }
        

        return $this->render('availibility/availibility_ajouter_modifier.html.twig', [
            "formAvailibility" => $form->createView(), 
            "availibility" => $availibility,
            "modification" => $availibility->getId() !== null
        ]);
    }






    /**
     * @Route("/gestion_availibility/supprimer/{id}", name="availibility_supprimer")
     */
    public function availibility_supprimer(Availibility $availibility, EntityManagerInterface $manager)
    {



        $nomAvailibility = $availibility->getName();
        $idAvailibility = $availibility->getId();

        $manager->remove($availibility);
        $manager->flush();


        $this->addFlash('success', "La disponibilité $nomAvailibility a bien été supprimée");

        return $this->redirectToRoute("availibility_afficher");


    }
}
