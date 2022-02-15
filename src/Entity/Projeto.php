<?php

namespace App\Entity;

use App\Repository\ProjetoRepository;
use DateTime;
use DateTimeZone;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @UniqueEntity(fields={"nome"}, message="Já há um projeto com esse nome. Por favor escolha outro...")
 */
#[ORM\Entity(repositoryClass: ProjetoRepository::class)]
class Projeto
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100, unique: true)]
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

    #[ORM\ManyToOne(targetEntity: Funcionario::class, inversedBy: 'projetos')]
    #[ORM\JoinColumn(nullable: false)]
    private $coordenador;

    #[ORM\OneToMany(mappedBy: 'projeto', targetEntity: Task::class)]
    private $cod_tasks;

    #[ORM\Column(type: 'string', length: 500)]
    private $url_imgCover;

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

    public function setCreatedAt(): self
    {
        $date = new DateTimeImmutable('now', new DateTimeZone('America/Sao_Paulo'));
        $this->created_at = $date;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(): self
    {
        $date = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        $this->updated_at = $date;

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

    public function getUrlImgCover(): ?string
    {
        return $this->url_imgCover;
    }

    public function setUrlImgCover(string $url_imgCover): self
    {
        $this->url_imgCover = $url_imgCover;

        return $this;
    }
}
