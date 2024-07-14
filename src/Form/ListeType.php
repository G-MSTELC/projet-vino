<?php

namespace App\Form;

use App\Entity\Liste;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('created_at', DateTimeType::class, [
                'label' => 'Date de création',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'readonly' => true,
                    'class' => 'form-control', 
                ],
            ])
            ->add('updated_at', DateTimeType::class, [
                'label' => 'Dernière mise à jour',
                'widget' => 'single_text',
                'required' => false,
                'attr' => [
                    'readonly' => true,
                    'class' => 'form-control', 
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
                'attr' => ['class' => 'btn btn-primary'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Liste::class,
        ]);
    }
}
