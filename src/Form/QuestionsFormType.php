<?php

namespace App\Form;

use App\Entity\Difficulties;
use App\Entity\Questions;
use App\Entity\Themes;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuestionsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number')
            ->add('question')
            ->add('answer')
            ->add('anecdote')
            ->add('theme', EntityType::class, [
                'class' => Themes::class,
'choice_label' => 'id',
            ])
            ->add('difficulty', EntityType::class, [
                'class' => Difficulties::class,
'choice_label' => 'id',
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
