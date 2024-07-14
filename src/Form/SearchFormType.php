<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class SearchFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->setMethod('POST')
            ->add('search', TextType::class, [
                'required' => false,
                'attr' => ['placeholder' => 'Rechercher'],
            ])
            ->add('sort', ChoiceType::class, [
                'choices' => [
                    'Prix croissant' => 'asc',
                    'Prix décroissant' => 'desc',
                ],
                'required' => false,
                'placeholder' => 'Trier par prix',
            ])
            ->add('price', ChoiceType::class, [
                'choices' => [
                    '0-20' => '0-20',
                    '20-50' => '20-50',
                    '50-100' => '50-100',
                    '100+' => '100+',
                ],
                'required' => false,
                'placeholder' => 'Filtrer par prix',
            ])
            ->add('search_button', SubmitType::class, [
                'label' => 'Rechercher',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([]);
    }
}
