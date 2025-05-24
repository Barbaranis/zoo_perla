<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('message', TextareaType::class, [
                'label' => 'Votre avis',
                'required' => true,
                'attr' => [
                    'rows' => 4,
                    'placeholder' => 'Écrivez votre avis ici...',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Le message ne peut pas être vide.']),
                    new Length([
                        'min' => 5,
                        'minMessage' => 'Le message doit contenir au moins 5 caractères.',
                        'max' => 1000,
                    ]),
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
            'csrf_field_name' => '_token',
            'csrf_token_id' => 'avis_form',
        ]);
    }
}

