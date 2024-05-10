<?php

namespace App\Form;

use App\Repository\PromptTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChatFormType extends AbstractType
{
    public function __construct(private PromptTypeRepository $promptTypeRepository)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('promptTypeId', ChoiceType::class, [
                'choices' => $options['choices'],
                'multiple' => false,
            ])
            ->add('data', TextareaType::class)
            ->add('submit', SubmitType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $choices = [];
        foreach ($this->promptTypeRepository->findAll() as $promptType) {
            $choices[$promptType->getName()] = $promptType->getId();
        }

        $resolver->setDefaults([
            'choices' => $choices,
        ]);
    }
}
