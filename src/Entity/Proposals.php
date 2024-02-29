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
    private ?string $proposal_1 = null;

    #[ORM\Column(length: 255)]
    private ?string $proposal_2 = null;

    #[ORM\Column(length: 255)]
    private ?string $proposal_3 = null;

    #[ORM\Column(length: 255)]
    private ?string $proposal_4 = null;

    #[ORM\ManyToOne(inversedBy: 'proposals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Questions $question = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProposal_1(): ?string
    {
        return $this->proposal_1;
    }

    public function setProposal_1(string $proposal_1): static
    {
        $this->proposal_1 = $proposal_1;

        return $this;
    }

    public function getProposal_2(): ?string
    {
        return $this->proposal_2;
    }

    public function setProposal_2(string $proposal_2): static
    {
        $this->proposal_2 = $proposal_2;

        return $this;
    }

    public function getProposal_3(): ?string
    {
        return $this->proposal_3;
    }

    public function setProposal_3(string $proposal_3): static
    {
        $this->proposal_3 = $proposal_3;

        return $this;
    }

    public function getProposal_4(): ?string
    {
        return $this->proposal_4;
    }

    public function setProposal_4(string $proposal_4): static
    {
        $this->proposal_4 = $proposal_4;

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
