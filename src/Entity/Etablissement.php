<?php

namespace App\Entity;

use App\Enum\EtatEtabEnum;
use App\Enum\SecteurEnum;
use App\Repository\EtablissementRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EtablissementRepository::class)]
class Etablissement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 8, max: 8)]
    private ?string $numero_uai = null;

    #[ORM\Column(enumType: SecteurEnum::class)]
    private ?SecteurEnum $secteur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $appellation_officielle = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresse = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Range(min: 1000, max: 99999)]
    #[Assert\Type(type: 'integer')]
    private ?int $code_postal = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $commune = null;


    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type(type: 'float')]
    #[Assert\Range(min: -90, max: 90)]
    private ?float $latitude = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type(type: 'float')]
    #[Assert\Range(min: -180, max: 180)]
    private ?float $longitude = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type(type: 'integer')]
    private ?int $nature_uai = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $nature_libe = null;


    #[ORM\Column(enumType: EtatEtabEnum::class)]
    #[Assert\NotBlank]
    private ?EtatEtabEnum $etat_etablissement_libe = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $code_departement = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type(type: 'integer')]
    private ?int $code_region = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type(type: 'integer')]
    private ?int $code_academie = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $code_commune = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 3)]
    private ?string $departement = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $region = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $academie = null;

    #[ORM\Column(nullable: true)]
    #[Assert\Type(type: 'integer')]
    private ?int $type_contrat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $libelle_type_contrat = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Type(type: 'integer')]
    private ?int $code_ministere = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $libelle_ministere = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull]
    #[CustomAssert\DateOpeningConstraint]
    private ?\DateTimeInterface $date_ouverture = null;

    /**
     * @var Collection<int, Commentaire>
     */
    #[ORM\OneToMany(mappedBy: 'etablissement', targetEntity: Commentaire::class, cascade: ['remove'], orphanRemoval: true)]
    private Collection $commentaires;

    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumeroUai(): ?string
{
    return $this->numero_uai;
}

    public function setNumeroUai(string $numero_uai): self
    {
        $this->numero_uai = $numero_uai;
        return $this;

    }

    public function getSecteur(): ?SecteurEnum
    {
        return $this->secteur;
    }

    public function setSecteur(string|SecteurEnum $secteur): static
    {
        if (is_string($secteur)) {
            // Convertir la chaîne en instance de SecteurEnum
            $secteur = SecteurEnum::tryFrom($secteur);
            if (!$secteur) {
                throw new \InvalidArgumentException("Valeur secteur invalide : $secteur");
            }
        }

        // Assurez-vous que $secteur est une instance de SecteurEnum
        $this->secteur = $secteur;

        return $this;
    }

    public function getAppellationOfficielle(): ?string
    {
        return $this->appellation_officielle;
    }

    public function setAppellationOfficielle(string $appellation_officielle): static
    {
        $this->appellation_officielle = $appellation_officielle;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?int
    {
        return $this->code_postal;
    }

    public function setCodePostal(int $code_postal): static
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getCommune(): ?string
    {
        return $this->commune;
    }

    public function setCommune(string $commune): static
    {
        $this->commune = $commune;

        return $this;
    }


    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getNatureUai(): ?int
    {
        return $this->nature_uai;
    }

    public function setNatureUai(int $nature_uai): static
    {
        $this->nature_uai = $nature_uai;

        return $this;
    }

    public function getNatureLibe(): ?string
    {
        return $this->nature_libe;
    }

    public function setNatureLibe(string $nature_libe): static
    {
        $this->nature_libe = $nature_libe;

        return $this;
    }

    public function getEtatEtablissementLibe(): ?EtatEtabEnum
    {
        return $this->etat_etablissement_libe;
    }

    public function setEtatEtablissementLibe(string|EtatEtabEnum $etat_etablissement_libe): static
    {
        if (is_string($etat_etablissement_libe)) {
            $etat_etablissement_libe = EtatEtabEnum::tryFrom($etat_etablissement_libe);
            if (!$etat_etablissement_libe) {
                throw new \InvalidArgumentException("Valeur état établissement invalide : $etat_etablissement_libe");
            }
        }

        $this->etat_etablissement_libe = $etat_etablissement_libe;

        return $this;
    }

    public function getCodeDepartement(): ?string
    {
        return $this->code_departement;
    }

    public function setCodeDepartement(string $code_departement): static
    {
        $this->code_departement = $code_departement;

        return $this;
    }

    public function getCodeRegion(): ?int
    {
        return $this->code_region;
    }

    public function setCodeRegion(int $code_region): static
    {
        $this->code_region = $code_region;

        return $this;
    }

    public function getCodeAcademie(): ?int
    {
        return $this->code_academie;
    }

    public function setCodeAcademie(int $code_academie): static
    {
        $this->code_academie = $code_academie;

        return $this;
    }

    public function getCodeCommune(): ?string
    {
        return $this->code_commune;
    }

    public function setCodeCommune(string $code_commune): static
    {
        $this->code_commune = $code_commune;

        return $this;
    }

    public function getDepartement(): ?string
    {
        return $this->departement;
    }

    public function setDepartement(string $departement): static
    {
        $this->departement = $departement;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): static
    {
        $this->region = $region;

        return $this;
    }

    public function getAcademie(): ?string
    {
        return $this->academie;
    }

    public function setAcademie(string $academie): static
    {
        $this->academie = $academie;

        return $this;
    }



    public function getTypeContrat(): ?int
    {
        return $this->type_contrat;
    }

    public function setTypeContrat(?int $type_contrat): static
    {
        $this->type_contrat = $type_contrat;

        return $this;
    }

    public function getLibelleTypeContrat(): ?string
    {
        return $this->libelle_type_contrat;
    }

    public function setLibelleTypeContrat(?string $libelle_type_contrat): static
    {
        $this->libelle_type_contrat = $libelle_type_contrat;

        return $this;
    }

    public function getCodeMinistere(): ?int
    {
        return $this->code_ministere;
    }

    public function setCodeMinistere(int $code_ministere): static
    {
        $this->code_ministere = $code_ministere;

        return $this;
    }

    public function getLibelleMinistere(): ?string
    {
        return $this->libelle_ministere;
    }

    public function setLibelleMinistere(string $libelle_ministere): static
    {
        $this->libelle_ministere = $libelle_ministere;

        return $this;
    }

    public function getDateOuverture(): ?\DateTimeInterface
    {
        return $this->date_ouverture;
    }

    public function setDateOuverture(\DateTimeInterface $date_ouverture): static
    {
        $this->date_ouverture = $date_ouverture;

        return $this;
    }

    /**
     * @return Collection<int, Commentaire>
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): static
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires->add($commentaire);
            $commentaire->setEtablissement($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): static
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getEtablissement() === $this) {
                $commentaire->setEtablissement(null);
            }
        }

        return $this;
    }
}
