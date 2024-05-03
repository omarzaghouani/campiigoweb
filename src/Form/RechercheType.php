<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ville', ChoiceType::class, [
                'choices' => [
                    'Tunis' => 'Tunis',
                    'Ariana' => 'Ariana',
                    'Manouba' => 'Manouba',
                    'Bizerte' => 'Bizerte',
                    'Jendouba' => 'Jendouba',
                    'Beja' => 'Beja',
                    'Kef' => 'Kef',
                    'Sousse' => 'Sousse',
                    'Monastir' => 'Monastir',
                    'Kaierouan' => 'Kaierouan',
                    'Gasrine' => 'Gasrine',
                    'Sfax' => 'Sfax',
                    'Tataouin' => 'Tataouin',
                    'Mednine' => 'Mednine',
                    'Jerba' => 'Jerba',
                    'Gabes' => 'Gabes',
                    'Mahdia' => 'Mahdia',
                    'Nabeul' => 'Nabeul',
                    'Gafsa' => 'Gafsa',
                    'Tozeur' => 'Tozeur',
                    'Sidi bouzid' => 'Sidi bouzid',
                    'Zaghouen' => 'Zaghouen',
                    'Kebili' => 'Kebili',
                    'Hamamet' => 'Hamamet',
                ],
                'placeholder' => 'Sélectionnez une ville', // Optionnel, pour afficher un choix par défaut vide
                'required' => false, // Rendre le champ facultatif
                // Autres options du champ...
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configurez ici les options du formulaire
        ]);
    }
}
