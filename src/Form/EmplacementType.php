<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use App\Entity\Emplacement; // Import the Emplacement entity class
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class EmplacementType extends AbstractType

{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('superficie', TextType::class)
            ->add('disponibilite', ChoiceType::class, [
                'label' => 'Disponibilité',
                'required' => false,
                'choices' => [
                    'Oui' => 1,
                    'Non' => 0,
                ],
                'expanded' => true, // Utilisez le widget "checkboxes"
                'multiple' => false, // Sélection unique
            ])
            ->add('centre')
            ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Emplacement::class,
        ]);
    }
}
