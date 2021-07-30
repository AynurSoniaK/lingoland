<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\MemberType;
use App\Form\ModifierProfilType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


class MemberController extends AbstractController
{
    /**
     * @Route("/member", name="member_profile")
     */
    public function afficher_profil(): Response
    {
        $member = $this->getUser();
        //dd($member);
        return $this->render(
            'member_controler/profile.html.twig',
            [
                'member' => $member
            ]
        );
    }

    /**
     * La fonction modifier_profil() permet de modifier le profil du membre
     * @Route("/member/modifier/{id}", name="profil_modifier")
     */
    public function modifier_profil(User $user, Request $request, EntityManagerInterface $manager)
    {
        $form = $this->createForm(ModifierProfilType::class, $user);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
        $photoFile = $form->get('photo')->getData();
        if ($photoFile) {
            // redéfinir le nom de l'image

            $nomPhoto = date("YmdHis") . "-" . uniqid() . "-" . $photoFile->getClientOriginalName();
            //dd($nomImage);

            // envoie de l'image dans le dossier public/images 

            try // on exécute le code dans try
            {
                $photoFile->move(
                    $this->getParameter('profil_photos'),
                    $nomPhoto
                );

                // méthode move() permet de déplacer un fichier
                // 2 arguments :
                // 1e : le "placement"
                // 2e : le nom
            } catch (FileException $e) // s'il y a une erreur, on récupère l'erreur et on l'affiche ici
            {
            }

            if(!empty($user->getPhoto() ))
            {
                unlink($this->getParameter('profil_photos') .'/'. $user->getPhoto());
            }
            // Envoie du nouveau nom en bdd

            $user->setPhoto($nomPhoto);
        }

        $manager->persist($user);
        $manager->flush();

        return $this->redirectToRoute("member_profile");
    }
    return $this->render("member_controler/modifier_profil.html.twig" , [
        "modifier_profilForm" => $form->createView(),
        "user" => $user
    ]);
  }

    /**
     * la fonction fiche_membre() permet d'afficher la fiche d'un membre existant 
     * 
     * @Route("/fiche_membre/{id}", name="fiche_membre")
     */
    public function fiche_membre(User $user)
    {                           // $id, membreRepository $repomembre
        return $this->render('member_controler/fiche_membre.html.twig', [
            'user' => $user
        ]);
    }
}