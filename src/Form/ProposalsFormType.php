<?php

namespace App\Form;

use App\Entity\Proposals;
use App\Entity\Questions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ProposalsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstProposal', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-white text-dark'
                ],
                'label' => 'Proposition n°1 :'
            ])
            ->add('secondProposal', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-white text-dark'
                ],
                'label' => 'Proposition n°2 :'
            ])
            ->add('thirdProposal', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-white text-dark'
                ],
                'label' => 'Proposition n°3 :'
            ])
            ->add('fourthProposal', TextType::class, [
                'attr' => [
                    'class' => 'form-control bg-white text-dark'
                ],
                'label' => 'Proposition n°4 :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Proposals::class,
        ]);
    }
}
