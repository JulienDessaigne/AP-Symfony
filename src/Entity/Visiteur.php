<?php

namespace App\Entity;

use App\Repository\VisiteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=VisiteurRepository::class)
 */
class Visiteur
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=30)
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $login;

    /**
     * @ORM\Column(type="string", length=20)
     */
    private $mdp;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=5, nullable=true)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="date")
     */
    private $dateEmbauche;

    /**
     * @ORM\Column(type="string", length=4)
     */
    private $ancienId;

    /**
     * @ORM\OneToMany(targetEntity=FicheFrais::class, mappedBy="idVisiteur", orphanRemoval=true)
     */
    private $lesFichesFrais;

    /**
     * @ORM\OneToMany(targetEntity=LigneFraisHorsForfait::class, mappedBy="idVisiteur", orphanRemoval=true)
     */
    private $lesLignesFraisHorsForfaits;

    /**
     * @ORM\OneToMany(targetEntity=LigneFraisForfait::class, mappedBy="idVisiteur", orphanRemoval=true)
     */
    private $lesLigneFraisForfait;

    public function __construct()
    {
        $this->lesFichesFrais = new ArrayCollection();
        $this->lesLignesFraisHorsForfaits = new ArrayCollection();
        $this->lesLigneFraisForfait = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getMdp(): ?string
    {
        return $this->mdp;
    }

    public function setMdp(string $mdp): self
    {
        $this->mdp = $mdp;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(?string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getDateEmbauche(): ?\DateTimeInterface
    {
        return $this->dateEmbauche;
    }

    public function setDateEmbauche(\DateTimeInterface $dateEmbauche): self
    {
        $this->dateEmbauche = $dateEmbauche;

        return $this;
    }

    public function getAncienId(): ?string
    {
        return $this->ancienId;
    }

    public function setAncienId(string $ancienId): self
    {
        $this->ancienId = $ancienId;

        return $this;
    }

    /**
     * @return Collection|FicheFrais[]
     */
    public function getLesFichesFrais(): Collection
    {
        return $this->lesFichesFrais;
    }

    public function addLesFichesFrai(FicheFrais $lesFichesFrai): self
    {
        if (!$this->lesFichesFrais->contains($lesFichesFrai)) {
            $this->lesFichesFrais[] = $lesFichesFrai;
            $lesFichesFrai->setIdVisiteur($this);
        }

        return $this;
    }

    public function removeLesFichesFrai(FicheFrais $lesFichesFrai): self
    {
        if ($this->lesFichesFrais->removeElement($lesFichesFrai)) {
            // set the owning side to null (unless already changed)
            if ($lesFichesFrai->getIdVisiteur() === $this) {
                $lesFichesFrai->setIdVisiteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LigneFraisHorsForfait[]
     */
    public function getLesLignesFraisHorsForfaits(): Collection
    {
        return $this->lesLignesFraisHorsForfaits;
    }

    public function addLesLignesFraisHorsForfait(LigneFraisHorsForfait $lesLignesFraisHorsForfait): self
    {
        if (!$this->lesLignesFraisHorsForfaits->contains($lesLignesFraisHorsForfait)) {
            $this->lesLignesFraisHorsForfaits[] = $lesLignesFraisHorsForfait;
            $lesLignesFraisHorsForfait->setIdVisiteur($this);
        }

        return $this;
    }

    public function removeLesLignesFraisHorsForfait(LigneFraisHorsForfait $lesLignesFraisHorsForfait): self
    {
        if ($this->lesLignesFraisHorsForfaits->removeElement($lesLignesFraisHorsForfait)) {
            // set the owning side to null (unless already changed)
            if ($lesLignesFraisHorsForfait->getIdVisiteur() === $this) {
                $lesLignesFraisHorsForfait->setIdVisiteur(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|LigneFraisForfait[]
     */
    public function getLesLigneFraisForfait(): Collection
    {
        return $this->lesLigneFraisForfait;
    }

    public function addLesLigneFraisForfait(LigneFraisForfait $lesLigneFraisForfait): self
    {
        if (!$this->lesLigneFraisForfait->contains($lesLigneFraisForfait)) {
            $this->lesLigneFraisForfait[] = $lesLigneFraisForfait;
            $lesLigneFraisForfait->setIdVisiteur($this);
        }

        return $this;
    }

    public function removeLesLigneFraisForfait(LigneFraisForfait $lesLigneFraisForfait): self
    {
        if ($this->lesLigneFraisForfait->removeElement($lesLigneFraisForfait)) {
            // set the owning side to null (unless already changed)
            if ($lesLigneFraisForfait->getIdVisiteur() === $this) {
                $lesLigneFraisForfait->setIdVisiteur(null);
            }
        }

        return $this;
    }
}
