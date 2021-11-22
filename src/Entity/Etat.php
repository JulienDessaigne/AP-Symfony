<?php

namespace App\Entity;

use App\Repository\EtatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatRepository::class)
 */
class Etat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=30, nullable=true)
     */
    private $libelle;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $iniLib;

    /**
     * @ORM\Column(type="string", length=2)
     */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

   

    public function getIniLib(): ?string
    {
        return $this->iniLib;
    }

    public function setIniLib(string $iniLib): self
    {
        $this->iniLib = $iniLib;

        return $this;
    }

    
}
