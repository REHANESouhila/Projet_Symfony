<?php
// src/DataFixtures/EtablissementFixtures.php

namespace App\DataFixtures;

use App\Entity\Etablissement;
use App\Enum\SecteurEnum;
use App\Enum\EtatEtabEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtablissementFixtures extends Fixture
{
    public const ETABLISSEMENT_REFERENCE_PREFIX = 'etab_';

    public function load(ObjectManager $manager): void
    {
        $csvFile = __DIR__ . '/data/fr-en-adresse-et-geolocalisation-etablissements-premier-et-second-degre.csv';
        if (!file_exists($csvFile)) {
            throw new \Exception("Fichier CSV non trouvé : $csvFile");
        }

        if (($handle = fopen($csvFile, 'r')) !== false) {
            // Lecture de la première ligne (en-tête) pour l’ignorer si nécessaire
            $header = fgetcsv($handle, 1000, ';');

            $batchSize = 20;
            $i = 0;

            while (($data = fgetcsv($handle, 10000, ';')) !== false) {
                $etab = new Etablissement();

                // (0) numero_uai -
                // (1) appellation_officielle


                $etab->setNumeroUai($data[0]);
                $etab->setAppellationOfficielle($data[1]);

                // (4) secteur (Enum)
                // Assurez-vous que la valeur CSV ("Public", "Privé"…) correspond à votre enum
                $etab->setSecteur(SecteurEnum::from($data[4]));

                // (5) adresse
                $etab->setAdresse($data[5]);

                // (8) code_postal
                $etab->setCodePostal((int)$data[8]);

                // (10) commune
                $etab->setCommune($data[10]);



                // (14) latitude
                $etab->setLatitude((float)$data[14]);

                // (15) longitude
                $etab->setLongitude((float)$data[15]);

                // (18) nature
                $etab->setNatureUai((int)$data[18]);

                // (19) naturel_libe
                $etab->setNatureLibe($data[19]);

                // (20) etat_etablissement_libe (Enum)
                $etab->setEtatEtablissementLibe(EtatEtabEnum::from($data[21]));

                // (21) code_departement
                $etab->setCodeDepartement($data[22]);

                // (22) code_region
                $etab->setCodeRegion((int)$data[23]);

                // (23) code_academie
                $etab->setCodeAcademie((int)$data[24]);

                // (24) code_commune
                $etab->setCodeCommune($data[25]);

                // (25) departement
                $etab->setDepartement($data[26]);

                // (26) region
                $etab->setRegion($data[27]);

                // (27) academie
                $etab->setAcademie($data[28]);

                // (28) type_contrat
                $etab->setTypeContrat(!empty($data[30]) ? (int)$data[30] : null);

                // (30) libelle_type_contrat
                $etab->setLibelleTypeContrat($data[31] ?: null);

                // (31) code_ministere
                $etab->setCodeMinistere((int)$data[32]);

                // (32) libelle_ministere
                $etab->setLibelleMinistere($data[33]);

                // (33) date_ouverture
                $etab->setDateOuverture(new \DateTime($data[34]));

                // Persiste l'entité
                $manager->persist($etab);


                // Gestion du flush/clear par lot
                if (($i % $batchSize) === 0) {
                    $manager->flush();
                    $manager->clear();
                }
                $i++;
            }

            // Fermeture du fichier et flush final
            fclose($handle);
            $manager->flush();
        }
    }
}
