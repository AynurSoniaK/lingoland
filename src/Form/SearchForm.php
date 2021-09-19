<?php

namespace App\Form;

use App\Data\SearchData;
use App\Entity\Language;
use App\Entity\Genre;
use App\Entity\LanguageLearned;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

class SearchForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'q',
                TextType::class,
                [
                    'label' => false,
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Rechercher'
                    ]
                ]
            )
            ->add(
                'languages',
                EntityType::class,
                [
                    'label' => false,
                    'required' => false,
                    'class' => Language::class,
                    'expanded' => true,
                    'multiple' => true
                ]
            )
            ->add(
                'languageslearned',
                EntityType::class,
                [
                    'label' => false,
                    'required' => false,
                    'class' => LanguageLearned::class,
                    'expanded' => true,
                    'multiple' => true
                ]
            )
            ->add(
                'gender',
                EntityType::class,
                [
                    'label' => false,
                    'required' => false,
                    'class' => Genre::class,
                    'expanded' => true,
                    'multiple' => true,
                ]
            )
            ->add('ageMin', NumberType::class,[
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Age min'
                ]
            ])
            ->add('ageMax', NumberType::class,[
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Age max'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => SearchData::class,
                'method' => 'GET',
                'csrf_protection' => false
            ]
        );
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
