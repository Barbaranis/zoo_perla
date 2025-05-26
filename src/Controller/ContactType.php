<?php




namespace App\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;


class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'constraints' => [new Assert\NotBlank(), new Assert\Length(['max' => 50])]
            ])
            ->add('email', EmailType::class, [
                'constraints' => [new Assert\NotBlank(), new Assert\Email()]
            ])
            ->add('telephone', TextType::class, [
                'constraints' => [new Assert\NotBlank(), new Assert\Regex('/^[0-9\s\-\+]{10,15}$/')]
            ])
            ->add('objet', ChoiceType::class, [
                'choices' => [
                    'Infos sur les visites' => 'visite',
                    'Questions sur les animaux' => 'animaux',
                    'Services du zoo' => 'service',
                    'Autre' => 'autre',
                ],
                'constraints' => [new Assert\NotBlank()]
            ])
            ->add('message', TextareaType::class, [
                'constraints' => [new Assert\NotBlank(), new Assert\Length(['min' => 10])]
            ])
            ->add('consentement', CheckboxType::class, [
                'mapped' => false,
                'label' => 'J’accepte que mes données soient utilisées pour me répondre.',
                'constraints' => [new Assert\IsTrue(message: 'Vous devez accepter le traitement des données.')]
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}



