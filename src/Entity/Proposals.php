<?php

namespace App\Entity;

use App\Repository\ProposalsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProposalsRepository::class)]
class Proposals
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $proposal = null;

    #[ORM\ManyToOne(inversedBy: 'proposals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Questions $question = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProposal(): ?string
    {
        return $this->proposal;
    }

    public function setProposal(string $proposal): static
    {
        $this->proposal = $proposal;

        return $this;
    }

    public function getIdQuestion(): ?Questions
    {
        return $this->question;
    }

    public function setIdQuestion(?Questions $question): static
    {
        $this->question = $question;

        return $this;
    }
}
