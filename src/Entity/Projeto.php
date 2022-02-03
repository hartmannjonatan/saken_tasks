<?php

namespace App\Entity;

use App\Repository\ProjetoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjetoRepository::class)]
class Projeto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $nome;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $updated_at;

    #[ORM\Column(type: 'string', length: 100)]
    private $cliente;

    #[ORM\Column(type: 'string', length: 255)]
    private $descricao;

    #[ORM\Column(type: 'string', length: 50)]
    private $slug;

    #[ORM\ManyToOne(targetEntity: funcionario::class, inversedBy: 'projetos')]
    #[ORM\JoinColumn(nullable: false)]
    private $coordenador;

    #[ORM\OneToMany(mappedBy: 'projeto', targetEntity: task::class)]
    private $cod_tasks;

    public function __construct()
    {
        $this->cod_tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getCliente(): ?string
    {
        return $this->cliente;
    }

    public function setCliente(string $cliente): self
    {
        $this->cliente = $cliente;

        return $this;
    }

    public function getDescricao(): ?string
    {
        return $this->descricao;
    }

    public function setDescricao(string $descricao): self
    {
        $this->descricao = $descricao;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getCoordenador(): ?funcionario
    {
        return $this->coordenador;
    }

    public function setCoordenador(?funcionario $coordenador): self
    {
        $this->coordenador = $coordenador;

        return $this;
    }

    /**
     * @return Collection|task[]
     */
    public function getCodTasks(): Collection
    {
        return $this->cod_tasks;
    }

    public function addCodTask(task $codTask): self
    {
        if (!$this->cod_tasks->contains($codTask)) {
            $this->cod_tasks[] = $codTask;
            $codTask->setProjeto($this);
        }

        return $this;
    }

    public function removeCodTask(task $codTask): self
    {
        if ($this->cod_tasks->removeElement($codTask)) {
            // set the owning side to null (unless already changed)
            if ($codTask->getProjeto() === $this) {
                $codTask->setProjeto(null);
            }
        }

        return $this;
    }
}
