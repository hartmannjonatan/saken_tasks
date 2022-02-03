<?php

namespace App\Entity;

use App\Repository\CargoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CargoRepository::class)]
class Cargo
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 50)]
    private $nome;

    #[ORM\OneToMany(mappedBy: 'cod_cargo', targetEntity: Funcionario::class)]
    private $funcionarios;

    public function __construct()
    {
        $this->funcionarios = new ArrayCollection();
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

    /**
     * @return Collection|Funcionario[]
     */
    public function getFuncionarios(): Collection
    {
        return $this->funcionarios;
    }

    public function addFuncionario(Funcionario $funcionario): self
    {
        if (!$this->funcionarios->contains($funcionario)) {
            $this->funcionarios[] = $funcionario;
            $funcionario->setCodCargo($this);
        }

        return $this;
    }

    public function removeFuncionario(Funcionario $funcionario): self
    {
        if ($this->funcionarios->removeElement($funcionario)) {
            // set the owning side to null (unless already changed)
            if ($funcionario->getCodCargo() === $this) {
                $funcionario->setCodCargo(null);
            }
        }

        return $this;
    }
}
