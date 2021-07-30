<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Hobbies;
use App\Entity\Language;
use App\Entity\Frequency;
use App\Entity\Availibility;
use App\Entity\Communication;
use App\Entity\LanguageLearned;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class ModifierProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'My name'))
            ->add('genre', EntityType::class,[
                "class" => Genre::class,
                'multiple' => false,
                "expanded" => true ])
            ->add('age', NumberType::class, array('label' => 'My age'))
            ->add('city', TextType::class, array('label' => 'My city'))
            ->add('introduction', TextType::class, array('label' => 'About me'))
            ->add('language', EntityType::class, [
                "class" => Language::class,
                'multiple' => true,
                "expanded" => true
            ])
            ->add('languageLearned', EntityType::class, [
                "class" => LanguageLearned::class,
                'multiple' => true,
                "expanded" => true
                
            ])
            ->add('availibility', EntityType::class, [
                'label' => 'I am available',
                "class" => Availibility::class,
                'multiple' => true,
                "expanded" => true
            ])
            ->add('communication', EntityType::class, [
                'label' => 'I want to communicate through',
                "class" => Communication::class,
                'multiple' => true,
                "expanded" => true
            ])
            ->add('frequency', EntityType::class, [
                "class" => Frequency::class,
                'label' => 'I am able to practice',
                'multiple' => false,
                "expanded" => true
            ])
            ->add('hobby', EntityType::class, [
                'label' => 'I like to speak about',
                "class" => Hobbies::class,
                'multiple' => true,
                "expanded" => true
            ])
            ->add('photo', FileType::class, [
                'mapped' => false,
                "required" => false,
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
