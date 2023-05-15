<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class SupportType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('objet', ChoiceType::class, [
            'choices' => [
                'Soucis de payement' => "Soucis de payement",
                'Problème de livraison' => "Problème de livraison",
                'Produit deffectueux' => "Produit deffectueux",
                'Mot de passe oublié' => "Mot de passe oublié",
                'Problème sur la sécurité de votre compte' => "Problème sur la sécurité du compte",
                'Autre' => "Autre",
            ],
            'attr' => [
                'class' => 'form-control'
            ],
            'label_attr' => [
                'class' => 'fw-bold'
            ]
        ])
        ->add('message', TextareaType::class, ['attr' => ['class'=> 'form-control', 'rows'=>'7', 'cols' => '7'], 'label_attr' => ['class'=> 'fw-bold']])
        ->add('envoyer', SubmitType::class, ['attr' => ['class'=> 'btn bg-primary text-white m-4' ], 'row_attr' => ['class' => 'text-center'],])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
