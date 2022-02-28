<?php

namespace App\Entity;

use App\Repository\PainelRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PainelRepository::class)]
class Painel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $title;

    #[ORM\OneToMany(mappedBy: 'painel', targetEntity: Note::class)]
    private $cod_notes;

    #[ORM\OneToOne(inversedBy: 'painel', targetEntity: Projeto::class, cascade: ['persist', 'remove'])]
    private $cod_projeto;

    public function __construct()
    {
        $this->cod_notes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|Note[]
     */
    public function getCodNotes(): Collection
    {
        return $this->cod_notes;
    }

    public function addCodNote(Note $codNote): self
    {
        if (!$this->cod_notes->contains($codNote)) {
            $this->cod_notes[] = $codNote;
            $codNote->setPainel($this);
        }

        return $this;
    }

    public function removeCodNote(Note $codNote): self
    {
        if ($this->cod_notes->removeElement($codNote)) {
            // set the owning side to null (unless already changed)
            if ($codNote->getPainel() === $this) {
                $codNote->setPainel(null);
            }
        }

        return $this;
    }

    public function getCodProjeto(): ?Projeto
    {
        return $this->cod_projeto;
    }

    public function setCodProjeto(?Projeto $cod_projeto): self
    {
        $this->cod_projeto = $cod_projeto;

        return $this;
    }
}
