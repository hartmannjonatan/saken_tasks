<?php

namespace App\Entity;

use App\Repository\FuncionarioRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: FuncionarioRepository::class)]
#[UniqueEntity('cpf')]
class Funcionario
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $nome;

    #[ORM\Column(type: 'string', unique: true)]
    private $cpf;

    #[ORM\Column(type: 'date')]
    private $data_nasc;

    #[ORM\ManyToOne(targetEntity: cargo::class, inversedBy: 'funcionarios')]
    #[ORM\JoinColumn(nullable: false)]
    private $cod_cargo;

    #[ORM\OneToMany(mappedBy: 'cod_func', targetEntity: Task::class)]
    private $tasks;

    #[ORM\OneToMany(mappedBy: 'coordenador', targetEntity: Projeto::class)]
    private $projetos;

    #[ORM\OneToOne(inversedBy: 'funcionario', targetEntity: user::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private $cod_user;

    public function __construct()
    {
        $this->tasks = new ArrayCollection();
        $this->projetos = new ArrayCollection();
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

    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    public function setCpf(string $cpf): self
    {
        $this->cpf = $cpf;

        return $this;
    }

    public function getDataNasc(): ?\DateTimeInterface
    {
        return $this->data_nasc;
    }

    public function setDataNasc(\DateTimeInterface $data_nasc): self
    {
        $this->data_nasc = $data_nasc;

        return $this;
    }

    public function getCodCargo(): ?cargo
    {
        return $this->cod_cargo;
    }

    public function setCodCargo(?cargo $cod_cargo): self
    {
        $this->cod_cargo = $cod_cargo;

        return $this;
    }

    /**
     * @return Collection|Task[]
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): self
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
            $task->setCodFunc($this);
        }

        return $this;
    }

    public function removeTask(Task $task): self
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getCodFunc() === $this) {
                $task->setCodFunc(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Projeto[]
     */
    public function getProjetos(): Collection
    {
        return $this->projetos;
    }

    public function addProjeto(Projeto $projeto): self
    {
        if (!$this->projetos->contains($projeto)) {
            $this->projetos[] = $projeto;
            $projeto->setCoordenador($this);
        }

        return $this;
    }

    public function removeProjeto(Projeto $projeto): self
    {
        if ($this->projetos->removeElement($projeto)) {
            // set the owning side to null (unless already changed)
            if ($projeto->getCoordenador() === $this) {
                $projeto->setCoordenador(null);
            }
        }

        return $this;
    }

    public function getCodUser(): ?user
    {
        return $this->cod_user;
    }

    public function setCodUser(user $cod_user): self
    {
        $this->cod_user = $cod_user;

        return $this;
    }
}
