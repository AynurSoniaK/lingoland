<?php

namespace App\Controller;

use App\Entity\Lessons;
use App\Form\LessonsType;
use App\Repository\LessonsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class LessonsController extends AbstractController
{

    /**
     * @Route("/gestion_lessons", name="lessons_afficher")
     */
    public function lessons_afficher(LessonsRepository $repoLessons): Response
    {
        return $this->render('lessons/lessons_afficher.html.twig', [
            'lesson' => $repoLessons->findAll(),
        ]);
    }

    /**
     * @Route("/gestion_lessons/ajouter", name="lessons_ajouter")
     * @Route("/gestion_lessons/modifier/{id}", name="lessons_modifier")
     */
    public function lessons_ajouter_modifier(Lessons $lessons = null, Request $request, EntityManagerInterface $manager)
    {
        if(!$lessons)
        {
            $lessons = new Lessons;
        }
        
        $form = $this->createForm(lessonsType::class, $lessons);

        $form->handleRequest($request); 

        if($form->isSubmitted() && $form->isValid())
        {
            $modif = $lessons->getId() !== null;
            $lessonFile = $form->get('lesson')->getData();
            $fileName = date("YmdHis") . "-" . uniqid() . "-" . $lessonFile->getClientOriginalName();
            $lessonFile->move($this->getParameter('lessons_directory'),$fileName);
            if(!empty($lessons->getLesson() ))
                {
                    unlink($this->getParameter('lessons_directory') .'/'. $lessons->getlesson());
                }
                
            $lessons->setLesson($fileName);
            $manager->persist($lessons); 
            $manager->flush(); 

            $this->addFlash('success', ($modif) ? "La leçon " . $lessons->getTitle() ." a bien été modifiée" : "La leçon " . $lessons->getTitle() ." a bien été ajoutée");

            return $this->redirectToRoute("lessons_afficher");

        }
        

        return $this->render('lessons/lessons_ajouter_modifier.html.twig', [
            "formLessons" => $form->createView(), 
            "lessons" => $lessons,
            "modification" => $lessons->getId() !== null
        ]);
    }

     /**
     * @Route("/gestion_lessons/supprimer/{id}", name="lessons_supprimer")
     */
    public function lessons_supprimer(Lessons $lessons, EntityManagerInterface $manager)
    {



        $nomLessons = $lessons->getTitle();
        $idLessons = $lessons->getId();
        if(!empty($lessons->getLesson() ))
        {
            unlink($this->getParameter('lessons_directory') .'/'. $lessons->getlesson());
        }

        $manager->remove($lessons);
        $manager->flush();


        $this->addFlash('success', "La disponibilité $nomLessons a bien été supprimée");

        return $this->redirectToRoute("lessons_afficher");


    }

}
