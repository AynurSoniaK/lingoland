<?php

namespace App\Controller;

use App\Entity\Language;
use App\Form\LanguageType;
use App\Repository\LanguageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class LanguageController extends AbstractController
{
    /**
     * @Route("/gestion_language", name="language_afficher")
     */
    public function language_afficher(LanguageRepository $repoLanguage): Response
    {
        return $this->render('language/language_afficher.html.twig', [
            "languages" => $repoLanguage->findAll()
        ]);
    }

    /**
     * @Route("/gestion_language/ajouter", name="language_ajouter")
     * @Route("/gestion_language/modifier/{id}", name="language_modifier")
     */
    public function language_ajouter_modifier(Language $language = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$language)
        {
            $language = new Language;
        }
        
        $form = $this->createForm(LanguageType::class, $language);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid())
        {
            $modif = $language->getId() !== null;
            $manager->persist($language); 
            $manager->flush(); 

            $this->addFlash('success', ($modif) ? "La langue " . $language->getName() ." a bien été modifiée" : "La langue " . $language->getName() ." a bien été ajoutée");

            return $this->redirectToRoute("language_afficher");


        }
        

        return $this->render('language/language_ajouter_modifier.html.twig', [
            "formLanguage" => $form->createView(), 
            "language" => $language,
            "modification" => $language->getId() !== null
        ]);
    }

    /**
     * @Route("/gestion_language/supprimer/{id}", name="language_supprimer")
     */
    public function language_supprimer(Language $language, EntityManagerInterface $manager)
    {

        $nomLanguage = $language->getName();
        $idLanguage = $language->getId();

        $manager->remove($language);
        $manager->flush();


        $this->addFlash('success', "La fréquence $nomLanguage a bien été supprimée");

        return $this->redirectToRoute("language_afficher");


    }
}