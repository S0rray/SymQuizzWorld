<?php

namespace App\Form;

use App\Entity\Themes;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;

class ThemesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', options:[
                'label' => 'Nom',
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Ce champ est requis'])
                ]
            ])
            ->add('difficulty', ChoiceType::class, [
                'label' => 'Difficulté',
                'required' => true,
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ]
            ])
            ->add('picture', FileType::class, [
                'label' => 'Bandeau',
                'multiple' => false,
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new Image([
                        'extensions' => [
                            'jpg',
                            'jpeg',
                            'png',
                            'webp'
                        ],
                        'extensionsMessage' => 'L\'image {{ name }} doit être de type {{ types }} et pas {{ type }} !',
                        'maxSize' => '1M',
                        'maxSizeMessage' => 'L\'image {{name }} ne doit pas dépasser la taille de {{ limite }}{{ suffix }} !'
                    ])
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Themes::class,
        ]);
    }
}
