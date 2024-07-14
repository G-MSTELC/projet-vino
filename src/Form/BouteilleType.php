<?php

namespace App\Form;

use App\Entity\Bouteille;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BouteilleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class)
            ->add('prix', TextType::class)
            ->add('pays', TextType::class)
            ->add('format', TextType::class)
            ->add('lienProduit', TextType::class)
            ->add('srcImage', TextType::class)
            ->add('srcsetImage', TextType::class)
            ->add('designation', TextType::class)
            ->add('degre', TextType::class, ['required' => false])
            ->add('tauxSucre', TextType::class, ['required' => false])
            ->add('couleur', TextType::class, ['required' => false])
            ->add('producteur', TextType::class, ['required' => false])
            ->add('agentPromotion', TextType::class, ['required' => false])
            ->add('type', TextType::class, ['required' => false])
            ->add('millesime', TextType::class, ['required' => false])
            ->add('cepage', TextType::class, ['required' => false])
            ->add('region', TextType::class, ['required' => false])
            ->add('produitQuebec', TextType::class, ['required' => false])
            ->add('pastilleGoutTitre', TextType::class, ['required' => false])
            ->add('pastilleImageSrc', TextType::class, ['required' => false])
            ->add('createdAt', TextType::class, ['required' => false])
            ->add('updatedAt', TextType::class, ['required' => false])
            ->add('user', TextType::class, ['required' => false]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Bouteille::class,
        ]);
    }
}
