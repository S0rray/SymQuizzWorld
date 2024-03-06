<?php

namespace App\Form;

use App\Entity\Themes;
use App\Entity\Questions;
use App\Entity\Difficulties;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class QuestionsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-white text-dark'
                ],
                'label' => 'Question :'
            ])
            ->add('answer', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-white text-dark'
                ],
                'label' => 'Réponse :'
            ])
            ->add('anecdote', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-white text-dark'
                ],
                'label' => 'Anecdote :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Questions::class,
        ]);
    }
}
