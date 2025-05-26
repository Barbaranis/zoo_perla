<?php



// src/Form/ContactType.php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\{TextType, EmailType, TelType, ChoiceType, TextareaType, CheckboxType};
use Symfony\Component\Validator\Constraints\{NotBlank, Length, Regex, Email, IsTrue};
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 2, 'max' => 50]),
                    new Regex([
                        'pattern' => '/^[a-zA-ZÀ-ÿ\'\s-]+$/',
                        'message' => 'Le nom contient des caractères invalides.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Email(),
                ],
            ])
            ->add('telephone', TelType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Regex([
                        'pattern' => '/^0[1-9](\d{2}){4}$/',
                        'message' => 'Numéro invalide (ex: 0612345678)',
                    ]),
                ],
            ])
            ->add('objet', ChoiceType::class, [
                'choices' => [
                    'Infos sur les visites' => 'visite',
                    'Questions sur les animaux' => 'animaux',
                    'Services du zoo' => 'service',
                    'Autre' => 'autre',
                ],
                'placeholder' => 'Choisissez un objet',
                'constraints' => [new NotBlank()],
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['min' => 10]),
                ],
            ])
            ->add('consentement', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue(['message' => 'Vous devez accepter le traitement de vos données.']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}
