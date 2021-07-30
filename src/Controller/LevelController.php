<?php

namespace App\Controller;

use App\Entity\Level;
use App\Form\LevelType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LevelRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class LevelController extends AbstractController
{
    /**
     * La fonction level_afficher() permet d'afficher sous forme de tableau la liste des level (BACK OFFICE)
     * On y trouvera le bouton pour ajouter une level
     * Chaque ligne du tableau on trouvera les liens de modifier et de supprimer 
     * 
     * @Route("/gestion_level", name="level_afficher")
     */
    public function level_afficher(LevelRepository $repoLevel)
    {
        return $this->render('level/level_afficher.html.twig', [
            "levels" => $repoLevel->findAll()
        ]);
    }


    

    /**
     * la fonction level_ajouter() permet d'ajouter une level 
     * Cette route se trouve sur la route level_afficher
     * 
     * @Route("/gestion_level/ajouter", name="level_ajouter")
     * @Route("/gestion_level/modifier/{id}", name="level_modifier")
     */
    public function level_ajouter_modifier(Level $level = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$level)
        {
            $level = new Level;
        }

        $form = $this->createForm(LevelType::class, $level);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid())
        {
            $modif = $level->getId() !== null;
            $manager->persist($level); 
            $manager->flush(); 

            $this->addFlash('success', ($modif) ? "Le niveau " . $level->getName() ." a bien été modifiée" : "Le niveau " . $level->getName() ." a bien été ajoutée");

            return $this->redirectToRoute("level_afficher");


        }
        

        return $this->render('level/level_ajouter_modifier.html.twig', [
            "formLevel" => $form->createView(), 
            "level" => $level,
            "modification" => $level->getId() !== null
        ]);
    }






    /**
     * @Route("/gestion_level/supprimer/{id}", name="level_supprimer")
     */
    public function level_supprimer(Level $level, EntityManagerInterface $manager)
    {



        $nomLevel = $level->getName();
        $idLevel = $level->getId();

        $manager->remove($level);
        $manager->flush();


        $this->addFlash('success', "Le niveau $nomLevel a bien été supprimée");

        return $this->redirectToRoute("level_afficher");


    }
}
