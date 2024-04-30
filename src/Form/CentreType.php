<?php

namespace App\Form;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Centre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType; // Ajoutez cette ligne pour importer FileType
use Symfony\Component\Validator\Constraints\File;

class CentreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('nom_centre')
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
            // Autres options du champ...
        ])
        ->add('mail')
        ->add('num_tel')
        ->add('nbre_activite')
        ->add('photo', FileType::class, [
            'label' => 'Photo (JPEG, PNG, GIF)',
            'mapped' => false,
            'required' => false, // Facultatif
            'constraints' => [
                new File([
                    'maxSize' => '1024k',
                    'mimeTypes' => [
                        'image/jpeg',
                        'image/png',
                        'image/gif',
                    ],
                    'mimeTypesMessage' => 'Veuillez télécharger un fichier image valide',
                ])
            ]
        ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Centre::class,
        ]);
    }
}
