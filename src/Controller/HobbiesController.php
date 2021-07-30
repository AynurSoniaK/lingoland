<?php

namespace App\Controller;

use App\Entity\Hobbies;
use App\Form\HobbiesType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\HobbiesRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class HobbiesController extends AbstractController
{
    /**
     * La fonction hobbies_afficher() permet d'afficher sous forme de tableau la liste des hobbies (BACK OFFICE)
     * On y trouvera le bouton pour ajouter une hobbies
     * Chaque ligne du tableau on trouvera les liens de modifier et de supprimer 
     * 
     * @Route("/gestion_hobbies", name="hobbies_afficher")
     */
    public function hobbies_afficher(HobbiesRepository $repoHobbies)
    {
        return $this->render('hobbies/hobbies_afficher.html.twig', [
            "hobby" => $repoHobbies->findAll()
        ]);
    }


    

    /**
     * la fonction hobbies_ajouter() permet d'ajouter une hobbies 
     * Cette route se trouve sur la route hobbies_afficher
     * 
     * @Route("/gestion_hobbies/ajouter", name="hobbies_ajouter")
     * @Route("/gestion_hobbies/modifier/{id}", name="hobbies_modifier")
     */
    public function hobbies_ajouter_modifier(Hobbies $hobbies = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$hobbies)
        {
            $hobbies = new Hobbies;
        }
        

        $form = $this->createForm(HobbiesType::class, $hobbies);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid())
        {
            $modif = $hobbies->getId() !== null;
            $manager->persist($hobbies); 
            $manager->flush(); 

            $this->addFlash('success', ($modif) ? "Le loisir" . $hobbies->getName() ." a bien été modifiée" : "Le loisir " . $hobbies->getName() ." a bien été ajoutée");

            return $this->redirectToRoute("hobbies_afficher");


        }
        

        return $this->render('hobbies/hobbies_ajouter_modifier.html.twig', [
            "formHobbies" => $form->createView(), 
            "hobbies" => $hobbies,
            "modification" => $hobbies->getId() !== null
        ]);
    }






    /**
     * @Route("/gestion_hobbies/supprimer/{id}", name="hobbies_supprimer")
     */
    public function hobbies_supprimer(Hobbies $hobbies, EntityManagerInterface $manager)
    {



        $nomHobbies = $hobbies->getName();
        $idHobbies = $hobbies->getId();

        $manager->remove($hobbies);
        $manager->flush();


        $this->addFlash('success', "Le loisir $nomHobbies a bien été supprimée");

        return $this->redirectToRoute("hobbies_afficher");


    }
}
