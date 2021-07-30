<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Hobbies;
use App\Entity\Genre;
use App\Entity\Language;
use App\Entity\Frequency;
use App\Entity\Availibility;
use App\Entity\Communication;
use App\Entity\LanguageLearned;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => 'Accepter les CGU',
                'constraints' => [
                    new IsTrue([
                        'message' => 'Veuillez accepter les conditions générales d\'utilisation.',
                    ]),
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => 'Accepter les CGU',
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez créer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('name',TextType::class, array('label'=>'Nom'))
            ->add('genre', EntityType::class,[
                "class" => Genre::class,
                'label'=>"Je suis un/une",
                'multiple' => false,
                "expanded" => true ])
            ->add('age',NumberType::class, array('label'=>'Age'))
            ->add('city',TextType::class, array('label'=>'Ville'))
            ->add('introduction',TextType::class, array('label'=>'Présentez-vous en quelques phrases'))
            ->add('language',EntityType::class,[
                'label'=>'Je parle',
                "class" => Language::class,
                "required" => true,
                'multiple' => true,
                "expanded" => true ])
            ->add('languageLearned',EntityType::class,[
                'label'=>'Je voudrais parler',
                "class" => LanguageLearned::class,
                'multiple' => true,
                "expanded" => true ])
            ->add('availibility',EntityType::class,[
                'label'=>'Je suis disponible',
                "class" => Availibility::class,
                'multiple' => true,
                "expanded" => true ])
            ->add('communication',EntityType::class,[
                'label'=>'J\'aimerais communiquer via',
                "class" => Communication::class,
                'multiple' => true,
                "expanded" => true ])
            ->add('frequency', EntityType::class,[
                "class" => Frequency::class,
                'label'=>'Je souhaite pratiquer',
                'multiple' => false,
                "expanded" => true ])
            ->add('hobby',EntityType::class,[
                'label'=>'J\'aime parler de',
                "class" => Hobbies::class,
                'multiple' => true,
                "expanded" => true ])
            ->add('photo', FileType::class, [
                "required" => false,
                'label'=>'Ajoutez une photo de profil',
                'constraints' => [
                    new File([
                            'mimeTypes' => [
                                "image/png",
                                "image/jpg",
                                "image/jpeg",

                            ],
                            'mimeTypesMessage' => "Extensions Autorisées : PNG JPG JPEG"
                        ])
                    ]
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
