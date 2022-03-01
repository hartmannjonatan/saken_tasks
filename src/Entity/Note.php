<?php

namespace App\Entity;

use App\Repository\NoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NoteRepository::class)]
class Note
{
    #[ORM\Id]
    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private $id;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private $title;

    #[ORM\Column(type: 'string', length: 1000, nullable: true)]
    private $conteudo;

    #[ORM\ManyToOne(targetEntity: Painel::class, inversedBy: 'cod_notes')]
    private $painel;

    #[ORM\Column(type: 'string', length: 20)]
    private $color;

    public function setId(string $idNote, string $idPainel): self
    {
        $id = $idNote."p".$idPainel;
        $this->id = $id;

        return $this;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title = "Título"): self
    {
        $this->title = $title;

        return $this;
    }

    public function getConteudo(): ?string
    {
        return $this->conteudo;
    }

    public function setConteudo(string $conteudo = "Conteúdo..."): self
    {
        $this->conteudo = $conteudo;

        return $this;
    }

    public function getPainel(): ?Painel
    {
        return $this->painel;
    }

    public function setPainel(?Painel $painel): self
    {
        $this->painel = $painel;

        return $this;
    }

    public function getColor(): ?string
    {
        return $this->color;
    }

    public function setColor(string $color): self
    {
        $this->color = $color;

        return $this;
    }
}
