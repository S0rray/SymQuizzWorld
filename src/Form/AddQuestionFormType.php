<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Form\QuestionsFormType;
use App\Form\ProposalsFormType;

class AddQuestionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('question', QuestionsFormType::class, [
            'label' => false,
        ])
        ->add('proposal', ProposalsFormType::class, [
            'label' => false,
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'validation_groups' => 'register'
        ]);
    }
}
