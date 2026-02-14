<?php

// src/Form/EtablissementType.php

namespace App\Form;

use App\Entity\Etablissement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Enum\SecteurEnum;
use App\Enum\EtatEtabEnum;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Doctrine\ORM\EntityManagerInterface;

class EtablissementType extends AbstractType
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Récupérer les valeurs uniques pour chaque champ depuis la base de données
        $departements = $this->entityManager->getRepository(Etablissement::class)
            ->createQueryBuilder('e')
            ->select('DISTINCT e.departement')
            ->getQuery()
            ->getResult();

        $regions = $this->entityManager->getRepository(Etablissement::class)
            ->createQueryBuilder('e')
            ->select('DISTINCT e.region')
            ->getQuery()
            ->getResult();

        $academies = $this->entityManager->getRepository(Etablissement::class)
            ->createQueryBuilder('e')
            ->select('DISTINCT e.academie')
            ->getQuery()
            ->getResult();

        $typeContrats = $this->entityManager->getRepository(Etablissement::class)
            ->createQueryBuilder('e')
            ->select('DISTINCT e.libelle_type_contrat')
            ->getQuery()
            ->getResult();

        $communes = $this->entityManager->getRepository(Etablissement::class)
            ->createQueryBuilder('e')
            ->select('DISTINCT e.commune')
            ->getQuery()
            ->getResult();

        $ministeres = $this->entityManager->getRepository(Etablissement::class)
            ->createQueryBuilder('e')
            ->select('DISTINCT e.libelle_ministere')
            ->getQuery()
            ->getResult();

        $builder
            ->add('numero_uai')
            ->add('secteur', ChoiceType::class, [
                'choices' => SecteurEnum::getChoices(),
                'label' => 'Secteur',
                'choice_value' => function ($secteur) {
                    if ($secteur instanceof SecteurEnum) {
                        return $secteur->value;
                    }
                    return $secteur;
                },
            ])
            ->add('appellation_officielle')
            ->add('adresse')
            ->add('code_postal')
            ->add('commune', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(function ($communes) { return $communes['commune']; }, $communes),
                    array_map(function ($communes) { return $communes['commune']; }, $communes)
                ),
                'label' => 'Commune',
            ])
            ->add('latitude')
            ->add('longitude')
            ->add('nature_uai')
            ->add('nature_libe')
            ->add('etat_etablissement_libe', ChoiceType::class, [
                'choices' => EtatEtabEnum::getChoices(),
                'label' => 'État de l\'établissement',
                'choice_value' => function ($etat) {
                    if ($etat instanceof EtatEtabEnum) {
                        return $etat->value;
                    }
                    return $etat;
                },
            ])
            ->add('code_departement')
            ->add('code_region')
            ->add('code_academie')
            ->add('code_commune')
            // Départements dynamiques
            ->add('departement', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(function ($departement) { return $departement['departement']; }, $departements),
                    array_map(function ($departement) { return $departement['departement']; }, $departements)
                ),
                'label' => 'Département',
            ])
            // Régions dynamiques
            ->add('region', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(function ($region) { return $region['region']; }, $regions),
                    array_map(function ($region) { return $region['region']; }, $regions)
                ),
                'label' => 'Région',
            ])
            // Académies dynamiques
            ->add('academie', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(function ($academie) { return $academie['academie']; }, $academies),
                    array_map(function ($academie) { return $academie['academie']; }, $academies)
                ),
                'label' => 'Académie',
            ])
            ->add('type_contrat')
            // Libellé type contrat dynamique
            ->add('libelle_type_contrat', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(function ($typeContrat) { return $typeContrat['libelle_type_contrat']; }, $typeContrats),
                    array_map(function ($typeContrat) { return $typeContrat['libelle_type_contrat']; }, $typeContrats)
                ),
                'label' => 'Libellé Type Contrat',
            ])
            ->add('code_ministere')
            // Libellé ministère dynamique
            ->add('libelle_ministere', ChoiceType::class, [
                'choices' => array_combine(
                    array_map(function ($ministere) { return $ministere['libelle_ministere']; }, $ministeres),
                    array_map(function ($ministere) { return $ministere['libelle_ministere']; }, $ministeres)
                ),
                'label' => 'Libellé Ministère',
            ])
            ->add('date_ouverture')
            ->add('submit', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etablissement::class,
        ]);
    }
}
