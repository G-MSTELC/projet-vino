<?php

namespace App\Form;

use App\Entity\Cellier;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class CellierType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'label' => 'Nom du cellier',
                'attr' => ['placeholder' => 'Entrez le nom du cellier']
            ])
            ->add('id', EntityType::class, [
                'class' => Cellier::class,
                'choice_label' => 'nom',
                'label' => 'Sélectionnez le cellier',
                'required' => false,
                'placeholder' => 'Tous les celliers', 
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cellier::class,
        ]);
    }
}
