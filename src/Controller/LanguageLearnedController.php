<?php

namespace App\Controller;

use App\Entity\LanguageLearned;
use App\Form\LanguageLearnedType;
use App\Entity\languageLearnedLearned;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LanguageLearnedRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class LanguageLearnedController extends AbstractController
{
    /**
     * @Route("/gestion_languagelearned", name="languagelearned_afficher")
     */
    public function languageLearned_afficher(LanguageLearnedRepository $repoLanguageLearned): Response
    {
        return $this->render('language_learned/languagelearned_afficher.html.twig', [
            "languageLearneds" => $repoLanguageLearned->findAll()
        ]);
    }

    /**
     * @Route("/gestion_languagelearned/ajouter", name="languagelearned_ajouter")
     * @Route("/gestion_languagelearned/modifier/{id}", name="languagelearned_modifier")
     */
    public function languageLearned_ajouter_modifier(LanguageLearned $languageLearned = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$languageLearned)
        {
            $languageLearned = new LanguageLearned;
        }
        
        $form = $this->createForm(LanguageLearnedType::class, $languageLearned);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid())
        {
            $modif = $languageLearned->getId() !== null;
            $manager->persist($languageLearned); 
            $manager->flush(); 

            $this->addFlash('success', ($modif) ? "La langue apprises " . $languageLearned->getName() ." a bien été modifiée" : "La langue apprises " . $languageLearned->getName() ." a bien été ajoutée");

            return $this->redirectToRoute("languagelearned_afficher");


        }
        

        return $this->render('language_learned/languagelearned_ajouter_modifier.html.twig', [
            "formLanguageLearned" => $form->createView(), 
            "LanguageLearned" => $languageLearned,
            "modification" => $languageLearned->getId() !== null
        ]);
    }

    /**
     * @Route("/gestion_languagelearned/supprimer/{id}", name="languagelearned_supprimer")
     */
    public function languageLearned_supprimer(LanguageLearned $languageLearned, EntityManagerInterface $manager)
    {

        $nomLanguageLearned = $languageLearned->getName();
        $idLanguageLearned = $languageLearned->getId();

        $manager->remove($languageLearned);
        $manager->flush();


        $this->addFlash('success', "La langue $nomLanguageLearned a bien été supprimée");

        return $this->redirectToRoute("languagelearned_afficher");


    }
}