<?php

namespace App\Controller;

use App\Entity\User;
use App\Data\SearchData;
use App\Form\MemberType;
use App\Form\SearchForm;
use App\Repository\UserRepository;
use App\Repository\LessonsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    /**
     * La fonction all_members() permet d'afficher tous les membres de la table user
     * 
     * @Route("/membres", name="all_members")
     */

    public function all_members(UserRepository $repoUser, Request $request): Response
    {
        $data = new SearchData();  
        $form = $this->createForm(SearchForm::class, $data);  
        $form->handleRequest($request);
        $users = $repoUser->findSearch($data); // à créer

        return $this->render('main/all_members.html.twig', [
            "users" => $users,
            "form" => $form->createView()
        ]);
    }

    /**
     * @Route("/lessons", name="all_lessons")
     */
    public function lessons_afficher(LessonsRepository $repoLessons): Response
    {
        return $this->render('main/all_lessons.html.twig', [
            'lesson' => $repoLessons->findAll(),
        ]);
    }

    /**
     * @Route("/mentions_legales", name="mentions")
     */
    public function mentions(): Response
    {
        return $this->render('main/mentions.html.twig');
    }

    /**
     * @Route("/confidentialite", name="confidentialite")
     */
    public function confidentialite(): Response
    {
        return $this->render('main/confidentialite.html.twig');
    }

}