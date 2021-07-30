<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Language;
use App\Repository\AvailibilityRepository;
use App\Repository\CommunicationRepository;
use App\Repository\FrequencyRepository;
use App\Repository\HobbiesRepository;
use App\Repository\LanguageLearnedRepository;
use App\Repository\LanguageRepository;
use App\Repository\LessonsRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



/**
 * On peut annoter un "prefixe" de route avant la class
 * Toutes les routes de cette class héritent de ce préfixe
 * dans Security.yaml (ligne 41), on a défini que toutes les routes commençant par /admin seront accéssible uniquement si le rôle est ROLE_ADMIN  
 * 
 * @Route("/admin")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/dashboard", name="admin")
     */
    public function dashboard(CommunicationRepository $repoCom, FrequencyRepository $repoFreq, AvailibilityRepository $repoAvail, HobbiesRepository $repoHob, LanguageRepository $repoLang, LessonsRepository $repoLess, UserRepository $repoUser, LanguageLearnedRepository $repoLangL): Response
    {
        $lang = $repoLang->findAll();
        $less = $repoLess->findAll();
        $user = $repoUser->findAll();
        $langL = $repoLangL->findAll();
        $avail = $repoAvail->findAll();
        $hob = $repoHob->findAll();
        $freq = $repoFreq->findAll();
        $com = $repoCom->findAll();
        $nb_lang=count($lang);
        $nb_less=count($less);
        $nb_user=count($user);
        $nb_langL=count($langL);
        $nb_hob=count($hob);
        $nb_avail=count($avail);
        $nb_freq=count($freq);
        $nb_com=count($com);
        return $this->render('admin/dashboard.html.twig',[
            "nb_lang" => $nb_lang,
            "nb_less" => $nb_less,
            "nb_user" => $nb_user,
            "nb_langL" => $nb_langL,
            "nb_hob" => $nb_hob,
            "nb_avail" => $nb_avail,
            "nb_freq" => $nb_freq,
            "nb_com" => $nb_com,
        ]);
    }

    /**
     * La fonction gestion_members() permet d'afficher tous les membres de la table user
     * 
     * @Route("/gestion_membres", name="gestion_members")
     */
    public function gestion_members(UserRepository $repoUser): Response
    {
        // entre les parenthèses de la fonction on apppelle les dependances
        // ==> tout ce qu'on a besoin dans la fonction

        //$repouser = $this->getDoctrine()->getRepository(user::class);
 
        $users = $repoUser->findAll(); // findAll = SELECT * FROM table
        //dd($users);

        // $tab=[];
        // foreach($users as $user){
        //     $var=$user->getDateAt();
        //     array_push($tab,$var);
        // }
        // dd($tab);

        //dd($tabDate);

        //dd($users);

        //dd($userId);



        // METHODES DU REPOSITORY

        // ->findAll() => SELECT * FROM user
        //$users = $repouser->findAll();



        // ->findById($id) => SELECT * FROM user WHERE id = $id
        // à l'interieur du tableau, est généré un tableau du user
        // $users = $repouser->findById(17);

        // ->find($id) => SELECT * FROM user WHERE id = $id
        // à l'intérieUr du tableau, on retrouve directement les données du user
        // $users = $repouser->find(17);


        // ->findBy() reçoit un tableau []
        // "nom du champ" => "valeur du champ"
        // SELECT * FROM user WHERE prix = 100 AND category = 1
        // ==> WHERE et AND
        // ->findBy([ 'prix'=> 100,'category' => 1 ]);

        //dd($users);
        // dump() : affiche dans la navbar de symfony, la "cible"
        // dump(); die; : die => la suite du code n'est pas "lue"
        // ==> dd();


        return $this->render('admin/gestion_membres.html.twig', [
            "users" => $users
        ]);
    }

    /**
       * La fonction supprimer_user() permet de modifier un user existant
       * 
       * @Route("/gestion_user/supprimer/{id}", name="supprimer_user")
    */

    public function supprimer_user(User $user, EntityManagerInterface $manager)
    {
      if(!empty($user->getPhoto()))
      {
          unlink($this->getParameter('profil_photos') .'/'. $user->getPhoto());
      }
      $manager->remove($user);
      $manager->flush();
      

      return $this->redirectToRoute("gestion_members");
    }
}

