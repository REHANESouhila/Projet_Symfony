<?php
namespace App\Controller;

use App\Entity\Commentaire;
use App\Entity\Etablissement;
use App\Form\CommentaireType;
use App\Repository\CommentaireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commentaires')]
class CommentaireController extends AbstractController
{
    private $entityManager;

    // Injecter l'EntityManagerInterface dans le constructeur
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/etablissement/{id}', name: 'commentaires_etablissement')]
    public function afficherCommentaires(Etablissement $etablissement, CommentaireRepository $commentaireRepository): Response
    {
        $commentaires = $commentaireRepository->findByEtablissement($etablissement);

        return $this->render('commentaires_etablissement.html.twig', [
            'etablissement' => $etablissement,
            'commentaires' => $commentaires,
        ]);
    }

    #[Route('/ajouter/{etablissement}', name: 'ajouter_commentaire')]
    public function ajouterCommentaire(Etablissement $etablissement, Request $request): Response
    {
        $commentaire = new Commentaire();
        $form = $this->createForm(CommentaireType::class, $commentaire);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commentaire->setEtablissement($etablissement);

            $this->entityManager->persist($commentaire);
            $this->entityManager->flush();

            return $this->redirectToRoute('commentaires_etablissement', ['id' => $etablissement->getId()]);
        }

        return $this->render('commentaires_ajouter.html.twig', [
            'etablissement' => $etablissement,
            'form' => $form->createView(),
        ]);
    }

    // Modification d'un commentaire
    #[Route('/commentaire/{id}/modifier', name: 'commentaire_modifier')]
    public function modifierCommentaire(int $id, Request $request, CommentaireRepository $commentaireRepository, EntityManagerInterface $entityManager): Response
    {
        $commentaire = $commentaireRepository->find($id);

        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire non trouvé');
        }

        // Récupérer l'établissement lié au commentaire
        $etablissement = $commentaire->getEtablissement();

        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();  // Sauvegarder les modifications dans la base de données
            $this->addFlash('success', 'Le commentaire a été modifié avec succès.');

            return $this->redirectToRoute('commentaires_etablissement', ['id' => $etablissement->getId()]);
        }

        return $this->render('commentaire_modifier.html.twig', [
            'form' => $form->createView(),
            'etablissement' => $etablissement,
        ]);
    }

    // Méthode qui renvoie à la page de confirmation pour supprimer un commentaire
    #[Route('/commentaire/{id}/confirmer-suppression', name: 'commentaire_confirmer_suppression')]
    public function confirmerSuppression(int $id, CommentaireRepository $commentaireRepository): Response
    {
        $commentaire = $commentaireRepository->find($id);

        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire non trouvé.');
        }

        return $this->render('commentaire_confirmation_suppression.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    // Méthode qui procède à la suppression du commenaire
    #[Route('/commentaire/{id}/supprimer', name: 'commentaire_supprimer', methods: ['POST'])]
    public function supprimerCommentaire(int $id, CommentaireRepository $commentaireRepository, EntityManagerInterface $entityManager): Response
    {
        $commentaire = $commentaireRepository->find($id);

        if (!$commentaire) {
            throw $this->createNotFoundException('Commentaire non trouvé.');
        }

        // Récupérer l'id de l'établissement associé au commentaire
        $etablissementId = $commentaire->getEtablissement()->getId();

        // Supprimer le commentaire
        $entityManager->remove($commentaire);
        $entityManager->flush();

        $this->addFlash('success', 'Le commentaire a été supprimé avec succès.');

        return $this->redirectToRoute('commentaires_etablissement', ['id' => $etablissementId]);
    }
}
