<?php

namespace App\Form;

use App\Entity\Commentaire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomAuteur', TextType::class, [
                'label' => 'Nom de l\'auteur'
            ])
            ->add('note', IntegerType::class, [
                'label' => 'Note (1-5)',
                'attr' => ['min' => 1, 'max' => 5], // Limiter la note de 1 à 5
            ])
            ->add('texte', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => ['rows' => 5], // Limiter la hauteur de la zone de texte pour le commentaire
            ])
            ->add('submit', SubmitType::class, ['label' => 'Modifier']); // Le bouton de soumission affiche "Modifier"
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class, // Assurez-vous de bien utiliser l'entité Commentaire
        ]);
    }
}
