<?php

namespace App\Form;

use App\Entity\Plateforme;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Count;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Image;

class AjoutProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('designation', TextType::class, [
            'attr' => ['class'=> 'form-control'], 
            'label_attr' => ['class'=> 'fw-white'],
            'error_bubbling' => true,
        ])
        ->add('description', TextareaType::class, [
            'attr' => ['class'=> 'form-control'], 
            'label_attr' => ['class'=> 'fw-white border-radius:20px;'], 
            'error_bubbling' => true,
        ])
        ->add('prix', NumberType::class, [
            'attr' => ['class'=> 'form-control'],
            'invalid_message' => 'Le prix doit être un nombre.',
            'constraints' => [
                new Type(['type' => 'numeric']),
            ],
            'error_bubbling' => true,
        ])
        ->add('plateformes', EntityType::class, [
            'class' => Plateforme::class,
            'choice_label' => 'libelle',
            'label' => 'Plateformes',
            'multiple' => true,
            'expanded' => true,
            'attr' => ['class' => 'form-check'],
            'label_attr' => ['class' => 'fw-white'],
            'error_bubbling' => true,
            'constraints' => [
                new Count([
                    'min' => 1,
                    'minMessage' => 'Veuillez sélectionner au moins une plateforme.',
                ]),
            ],
        ])
        ->add('image', FileType::class, [
            'data_class' => null,
            'label' => 'Image du produit à télécharger',
            'constraints' => [
                new File([
                    'maxSize' => '500k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/webp',
                        'image/jpg',
                        'image/JPG',
                        'image/png',
                    ],
                    'mimeTypesMessage' => 'Le site accepte uniquement les fichiers JPG, JEPG, WEBP et PNG',
                    'uploadErrorMessage' => 'Le fichier est trop volumineux. La taille maximale autorisée est de 0.5 Mo.',
                ]),
                /*new Callback(function ($data, ExecutionContextInterface $context) {
                    $image = getimagesize($data->getPathname());
                    if ($image[0] !== 1920 || $image[1] !== 1080) {
                        $context->buildViolation('La résolution de l\'image doit être de 1920x1080')
                            ->addViolation();
                    }
                }), A ADAPTER DANS LE CONTROLLER */
            ],
            'required' => false,
            'mapped'=>false,
            'error_bubbling' => true,
        ])
        ->add('ajouter', SubmitType::class, [
            'attr' => ['class'=> 'btn bg-primary text-white m-4' ],
            'row_attr' => ['class' => 'text-center'],
        ])
    ;
}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
