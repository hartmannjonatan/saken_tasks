<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use DateTime;
use DateTimeZone;
use DateTimeImmutable;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 100)]
    private $nome;

    #[ORM\Column(type: 'datetime_immutable')]
    private $created_at;

    #[ORM\Column(type: 'datetime_immutable')]
    private $conclued_at;

    #[ORM\Column(type: 'datetime')]
    private $final_date;

    #[ORM\Column(type: 'string', length: 255)]
    private $descricao;

    #[ORM\ManyToOne(targetEntity: funcionario::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private $cod_func;

    #[ORM\ManyToOne(targetEntity: Classificacao::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private $cod_class;

    #[ORM\ManyToOne(targetEntity: Projeto::class, inversedBy: 'cod_tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private $projeto;

    #[ORM\ManyToOne(targetEntity: Tipo::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false)]
    private $tipo;

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

    public function getConcluedAt(): ?\DateTimeImmutable
    {
        return $this->conclued_at;
    }

    public function setConcluedAt(\DateTimeImmutable $conclued_at = null): self
    {
        $date = new DateTime('now', new DateTimeZone('America/Sao_Paulo'));
        if($conclued_at == null){
            $this->conclued_at = null;
        } else $this->conclued_at = $date;

        return $this;
    }

    public function getFinalDate(): ?\DateTimeInterface
    {
        return $this->final_date;
    }

    public function setFinalDate(\DateTimeInterface $final_date): self
    {
        $this->final_date = $final_date;

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

    public function getCodFunc(): ?funcionario
    {
        return $this->cod_func;
    }

    public function setCodFunc(?funcionario $cod_func): self
    {
        $this->cod_func = $cod_func;

        return $this;
    }

    public function getCodClass(): ?Classificacao
    {
        return $this->cod_class;
    }

    public function setCodClass(?Classificacao $cod_class): self
    {
        $this->cod_class = $cod_class;

        return $this;
    }

    public function getProjeto(): ?Projeto
    {
        return $this->projeto;
    }

    public function setProjeto(?Projeto $projeto): self
    {
        $this->projeto = $projeto;

        return $this;
    }

    public function getTipo(): ?Tipo
    {
        return $this->tipo;
    }

    public function setTipo(?Tipo $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }
}
