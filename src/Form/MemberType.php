<?php

namespace App\Form;

use App\Entity\Member;
use App\Entity\Level;
use App\Entity\Availibility;
use App\Entity\Frequency;
use App\Entity\Hobbies;
use App\Entity\Communication;
use App\Entity\LanguageLearned;
use App\Entity\Language;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MemberType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name',TextType::class, array('label'=>'My name'))
            ->add('age',NumberType::class, array('label'=>'My age'))
            ->add('city',TextType::class, array('label'=>'My city'))
            ->add('introduction',TextType::class, array('label'=>'About me'))
            ->add('languages',EntityType::class,[
                "class" => Language::class,
                'multiple' => true,
                "expanded" => true ])
            ->add('languageLearneds',EntityType::class,[
                "class" => LanguageLearned::class,
                'multiple' => true,
                "expanded" => true ])
            ->add('level',EntityType::class,[
                'label'=>'Mon niveau',
                "class" => level::class,
                'multiple' => false,
                "expanded" => true ])
            ->add('availibilities',EntityType::class,[
                'label'=>'I am available',
                "class" => Availibility::class,
                'multiple' => true,
                "expanded" => true ])
            ->add('communications',EntityType::class,[
                'label'=>'I want to communicate through',
                "class" => Communication::class,
                'multiple' => true,
                "expanded" => true ])
            ->add('frequencies', EntityType::class,[
                "class" => Frequency::class,
                'label'=>'I am able to practice',
                'multiple' => true,
                "expanded" => true ])
            ->add('hobbies',EntityType::class,[
                'label'=>'I like to speak about',
                "class" => Hobbies::class,
                'multiple' => true,
                "expanded" => true ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Member::class,
        ]);
    }
}
