<?php

namespace App\Form;

use App\Entity\Classe;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;        // <-- Bonne classe
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClasseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la classe'
            ])
            ->add('niveau', TextType::class, [
                'label' => 'Niveau'
            ])
            ->add('anneeScolaire', TextType::class, [               // <-- TextType, pas Type::class
                'label' => 'Année scolaire',
                'data'  => date('Y') . '-' . (date('Y') + 1),       // valeur par défaut 2025-2026 par ex.
                'help'  => 'Format : 2025-2026'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classe::class,
        ]);
    }
}
