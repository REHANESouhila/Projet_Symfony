<?php

// src/Controller/EtablissementController.php
namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Etablissement;
use App\Form\EtablissementType;
use App\Repository\EtablissementRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;


#[Route('/etablissements')]
class EtablissementController extends AbstractController
{
    #[Route('/departement/{code_departement}', name: 'etablissements_par_departement')]
    public function parDepartement(string $code_departement, EtablissementRepository $repository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1); // Récupérer la page courante, 1 par défaut
        $limit = 50; // Nombre d'établissements par page
        $offset = ($page - 1) * $limit; // Calcul de l'offset

        // Construire la requête avec un QueryBuilder
        $query = $repository->createQueryBuilder('e')
            ->where('e.code_departement = :code_departement')
            ->setParameter('code_departement', $code_departement)
            ->setFirstResult($offset) // Définir l'offset
            ->setMaxResults($limit)  // Limiter les résultats à la page courante
            ->getQuery();

        // Appliquer la pagination
        $paginator = new Paginator($query);
        $total = count($paginator); // Nombre total d'établissements

        return $this->render('departement.html.twig', [
            'etablissements' => $paginator,
            'departement' => $code_departement,
            'current_page' => $page,
            'total_pages' => ceil($total / $limit), // Nombre total de pages
        ]);
    }


    #[Route('/Accueil', name: 'etablissements')]
  public function index(EtablissementRepository $etablissementRepository, Request $request): Response
  {
      // Pagination
      $page = $request->query->getInt('page', 1);
      $limit = 10;
      $offset = ($page - 1) * $limit;

      // Récupérer les filtres de recherche
      $UAI = $request->query->get('uai');
      $region = $request->query->get('region');
      $departement = $request->query->get('departement');
      $commune = $request->query->get('commune');
      $sortBy = $request->query->get('sortBy', 'departement');  // Tri par défaut par département

      // Construction de la requête
      $queryBuilder = $etablissementRepository->createQueryBuilder('e');

      // Filtrage par numéro UAI
      if ($UAI) {
          $queryBuilder->where('e.numero_uai = :uai')
              ->setParameter('uai', $UAI);
      }

      // Filtrage par région, département ou commune
      if ($region) {
          $queryBuilder->andWhere('e.code_region = :region')
              ->setParameter('region', $region);
      }

      if ($departement) {
          $queryBuilder->andWhere('e.code_departement = :departement')
              ->setParameter('departement', $departement);
      }

      if ($commune) {
          $queryBuilder->andWhere('e.code_commune = :commune')
              ->setParameter('commune', $commune);
      }

      // Appliquer le tri
      switch ($sortBy) {
          case 'region':
              $queryBuilder->orderBy('e.region', 'ASC');
              break;
          case 'commune':
              $queryBuilder->orderBy('e.commune', 'ASC');
              break;
          case 'departement':
          default:
              $queryBuilder->orderBy('e.departement', 'ASC');
              break;
      }

      // Limiter les résultats pour la pagination
      $queryBuilder->setFirstResult($offset)
                   ->setMaxResults($limit);

      // Exécuter la requête
      $etablissements = $queryBuilder->getQuery()->getResult();

      // Appliquer la pagination
      $paginator = new Paginator($queryBuilder);
      $total = count($paginator);

      // Récupérer les valeurs possibles pour les filtres
      $regions = $etablissementRepository->findDistinctRegions();
      $departements = $etablissementRepository->findDistinctDepartements();
      $communes = $etablissementRepository->findDistinctCommunes();

      return $this->render('index.html.twig', [
          'etablissements' => $etablissements,
          'regions' => $regions,
          'departements' => $departements,
          'communes' => $communes,
          'current_page' => $page,
          'total_pages' => ceil($total / $limit),
          'sortBy' => $sortBy, // Passer l'option de tri à la vue
      ]);
  }




    #[Route('/academie/{code_academie}', name: 'etablissements_par_academie')]
    public function parAcademie(string $code_academie, EtablissementRepository $repository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 100;
        $offset = ($page - 1) * $limit;

        $query = $repository->createQueryBuilder('e')
            ->where('e.code_academie = :code_academie')
            ->setParameter('code_academie', $code_academie)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $total = count($paginator);

        return $this->render('academie.html.twig', [
            'etablissements' => $paginator,
            'academie' => $code_academie,
            'current_page' => $page,
            'total_pages' => ceil($total / $limit),
        ]);
    }

    #[Route('/region/{code_region}', name: 'etablissements_par_region')]
    public function parRegion(string $code_region, EtablissementRepository $repository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 100;
        $offset = ($page - 1) * $limit;

        $query = $repository->createQueryBuilder('e')
            ->where('e.code_region = :code_region')
            ->setParameter('code_region', $code_region)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $total = count($paginator);

        return $this->render('region.html.twig', [
            'etablissements' => $paginator,
            'region' => $code_region,
            'current_page' => $page,
            'total_pages' => ceil($total / $limit),
        ]);
    }

    #[Route('/commune/{commune}', name: 'etablissements_par_commune')]
    public function parCommune(string $commune, EtablissementRepository $repository, Request $request): Response
    {
        $page = $request->query->getInt('page', 1);
        $limit = 50;
        $offset = ($page - 1) * $limit;

        $query = $repository->createQueryBuilder('e')
            ->where('e.code_commune = :code_commune')
            ->setParameter('code_commune', $commune)
            ->setFirstResult($offset)
            ->setMaxResults($limit)
            ->getQuery();

        $paginator = new Paginator($query);
        $total = count($paginator);

        return $this->render('commune.html.twig', [
            'etablissements' => $paginator,
            'commune' => $commune,
            'current_page' => $page,
            'total_pages' => ceil($total / $limit),
        ]);
    }

    #[Route('/new', name: 'etablissement_create')]
  public function create(Request $request, EntityManagerInterface $entityManager): Response
  {
      $etablissement = new Etablissement();
      $form = $this->createForm(EtablissementType::class, $etablissement);

      $form->handleRequest($request);
      if ($form->isSubmitted() && $form->isValid()) {
          $entityManager->persist($etablissement);
          $entityManager->flush();

          return $this->redirectToRoute('etablissements_par_departement', [
              'code_departement' => $etablissement->getCodeDepartement(),
          ]);
      }

      return $this->render('create.html.twig', [
          'form' => $form->createView(),
      ]);
  }


    // Affichage des détails d'un établissement depuis la commune
    #[Route('/commune/{commune}/detail/{numero_uai}', name: 'etablissement_detail_commune')]
    public function etablissementDetailCommune(string $commune, string $numero_uai, EtablissementRepository $repository): Response
    {
        $etablissement = $repository->findOneByNumeroUai($numero_uai);
        if (!$etablissement) {
            throw $this->createNotFoundException('Établissement non trouvé.');
        }

        return $this->render('commune_etablissement_detail.html.twig', [
            'etablissement' => $etablissement,
            'commune' => $commune,
        ]);
    }

    // Affichage des détails d'un établissement depuis le département
    #[Route('/departement/{code_departement}/detail/{numero_uai}', name: 'etablissement_detail_departement')]
    public function etablissementDetailDepartement(string $code_departement, string $numero_uai, EtablissementRepository $repository): Response
    {
        // Recherche de l'établissement par numéro UAI
        $etablissement = $repository->findOneByNumeroUai($numero_uai);

        if (!$etablissement) {
            throw $this->createNotFoundException('Établissement non trouvé.');
        }

        // Rendu du détail de l'établissement avec le département
        return $this->render('departement_etablissement_detail.html.twig', [
            'etablissement' => $etablissement,
            'departement' => $code_departement,
        ]);
    }

    // Affichage des détails d'un établissement depuis l'académie
    #[Route('/academie/{code_academie}/detail/{numero_uai}', name: 'etablissement_detail_academie')]
    public function etablissementDetailAcademie(string $code_academie, string $numero_uai, EtablissementRepository $repository): Response
    {
        $etablissement = $repository->findOneByNumeroUai($numero_uai);
        if (!$etablissement) {
            throw $this->createNotFoundException('Établissement non trouvé.');
        }

        return $this->render('academie_etablissement_detail.html.twig', [
            'etablissement' => $etablissement,
            'academie' => $code_academie,
        ]);
    }

    // Affichage des détails d'un établissement depuis la région
    #[Route('/region/{code_region}/detail/{numero_uai}', name: 'etablissement_detail_region')]
    public function etablissementDetailRegion(string $code_region, string $numero_uai, EtablissementRepository $repository): Response
    {
        $etablissement = $repository->findOneByNumeroUai($numero_uai);
        if (!$etablissement) {
            throw $this->createNotFoundException('Établissement non trouvé.');
        }

        return $this->render('region_etablissement_detail.html.twig', [
            'etablissement' => $etablissement,
            'region' => $code_region,
        ]);
    }

    #[Route('/modifier/{numero_uai}', name: 'modifier_etablissement')]
    public function modifierEtablissement(string $numero_uai, Request $request, EtablissementRepository $etablissementRepository): Response
    {
        // Trouver l'établissement par son numero_uai
        $etablissement = $etablissementRepository->findOneByNumeroUai($numero_uai);

        if (!$etablissement) {
            throw $this->createNotFoundException('Aucun établissement trouvé avec ce numéro UAI.');
        }

        $form = $this->createForm(EtablissementType::class, $etablissement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $etablissementRepository->save($etablissement, true);

            $this->addFlash('success', 'L\'établissement a été mis à jour avec succès.');
            return $this->redirectToRoute('modifier_etablissement', ['numero_uai' => $numero_uai]);
        }

        return $this->render('modifier.html.twig', [
            'form' => $form->createView(),
            'etablissement' => $etablissement,
        ]);
    }

    // La méthode qui renvoie à la page de confirmation de suppression
    #[Route('/confirmer-suppression/{numero_uai}/{source}', name: 'confirmer_suppression')]
    public function confirmerSuppression(string $numero_uai, string $source, EtablissementRepository $repository): Response
    {
        $etablissement = $repository->findOneByNumeroUai($numero_uai);

        if (!$etablissement) {
            throw $this->createNotFoundException('Établissement non trouvé.');
        }

        return $this->render('confirmation_suppression.html.twig', [
            'etablissement' => $etablissement,
            'source' => $source,
        ]);
    }


    #[Route('/supprimer/{numero_uai}/{source}', name: 'supprimer_etablissement', methods: ['POST'])]
    public function supprimerEtablissement(string $numero_uai, string $source, EtablissementRepository $repository, EntityManagerInterface $entityManager): Response
    {
        // Récupérer l'établissement à supprimer
        $etablissement = $repository->findOneByNumeroUai($numero_uai);

        if (!$etablissement) {
            throw $this->createNotFoundException('Établissement non trouvé.');
        }

        // Supprimer les commentaires associés à l'établissement
        $commentaires = $etablissement->getCommentaires();
        foreach ($commentaires as $commentaire) {
            $entityManager->remove($commentaire);
        }

        // Supprimer l'établissement
        $entityManager->remove($etablissement);
        $entityManager->flush();
        $this->addFlash('success', 'L\'établissement a été supprimé avec succès.');

        switch ($source) {
            case 'departement':
                return $this->redirectToRoute('etablissements_par_departement', ['code_departement' => $etablissement->getCodeDepartement()]);
            case 'academie':
                return $this->redirectToRoute('etablissements_par_academie', ['code_academie' => $etablissement->getCodeAcademie()]);
            case 'region':
                return $this->redirectToRoute('etablissements_par_region', ['code_region' => $etablissement->getCodeRegion()]);
            case 'commune':
                return $this->redirectToRoute('etablissements_par_commune', ['commune' => $etablissement->getCodeCommune()]);
            default:
                return $this->redirectToRoute('home');
        }
    }



    // La méthode cartographique pour afficher la carte des établissements par commune
    #[Route('/cartographieCommune/{idCommune}', name: 'cartographie_commune')]
    public function cartographieCommune(string $idCommune, EtablissementRepository $repository): Response
    {
        // Récupérer les établissements pour la commune donnée
        $etablissements = $repository->createQueryBuilder('e')
            ->where('e.code_commune = :code_commune')
            ->setParameter('code_commune', $idCommune)
            ->getQuery()
            ->getResult();
        $data = [];
        foreach ($etablissements as $etablissement) {
            $data[] = [
                'nom' => $etablissement->getAppellationOfficielle(),
                'departement' => $etablissement->getDepartement(),
                'commune' => $etablissement -> getCommune(),
                'lat' => $etablissement->getLatitude(),
                'lon' => $etablissement->getLongitude(),
            ];
        }


        return $this->render('cartographie_commune.html.twig', [
            'commune' => $idCommune,
            'data' => $data
        ]);
    }
}
