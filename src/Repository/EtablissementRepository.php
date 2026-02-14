<?php

namespace App\Repository;

use App\Entity\Etablissement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Etablissement>
 */
class EtablissementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Etablissement::class);
    }

    /**
     * Trouver un établissement par son numéro UAI.
     *
     * @param string $numeroUai Le numéro UAI de l'établissement.
     * @return Etablissement|null L'établissement trouvé ou null.
     */
    public function findOneByNumeroUai(string $numero_uai): ?Etablissement
    {
        return $this->createQueryBuilder('e')
            ->where('e.numero_uai = :numero_uai')
            ->setParameter('numero_uai', $numero_uai)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Sauvegarde une entité dans la base de données.
     *
     * @param Etablissement $etablissement L'entité à sauvegarder
     * @param bool $flush Si true, la transaction est exécutée immédiatement
     */
    public function save(Etablissement $etablissement, bool $flush = false): void
    {
        $this->getEntityManager()->persist($etablissement);
        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUai(string $numero_uai)
{
    return $this->createQueryBuilder('e')
        ->andWhere('e.numeroUai = :uai')
        ->setParameter('uai', $numero_uai)
        ->getQuery()
        ->getResult();
}
public function findDistinctRegions()
{
    return $this->createQueryBuilder('e')
        ->select('DISTINCT e.code_region')
        ->getQuery()
        ->getResult();
}

public function findDistinctDepartements()
{
    return $this->createQueryBuilder('e')
        ->select('DISTINCT e.code_departement')
        ->getQuery()
        ->getResult();
}

public function findDistinctCommunes()
{
    return $this->createQueryBuilder('e')
        ->select('DISTINCT e.code_commune')
        ->getQuery()
        ->getResult();
}
//    /**
//     * @return Etablissement[] Returns an array of Etablissement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Etablissement
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
