<?php

namespace App\Entity;

use App\Repository\ProposalsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProposalsRepository::class)]
class Proposals
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Veuillez insérer une proposition de réponse !')]
    private ?string $firstProposal = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Veuillez insérer une proposition de réponse !')]
    private ?string $secondProposal = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Veuillez insérer une proposition de réponse !')]
    private ?string $thirdProposal = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'Veuillez insérer une proposition de réponse !')]
    private ?string $fourthProposal = null;

    #[ORM\ManyToOne(inversedBy: 'proposals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Questions $question = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstProposal(): ?string
    {
        return $this->firstProposal;
    }

    public function setFirstProposal(string $firstProposal): static
    {
        $this->firstProposal = $firstProposal;

        return $this;
    }

    public function getSecondProposal(): ?string
    {
        return $this->secondProposal;
    }

    public function setSecondProposal(string $secondProposal): static
    {
        $this->secondProposal = $secondProposal;

        return $this;
    }

    public function getThirdProposal(): ?string
    {
        return $this->thirdProposal;
    }

    public function setThirdProposal(string $thirdProposal): static
    {
        $this->thirdProposal = $thirdProposal;

        return $this;
    }

    public function getFourthProposal(): ?string
    {
        return $this->fourthProposal;
    }

    public function setFourthProposal(string $fourthProposal): static
    {
        $this->fourthProposal = $fourthProposal;

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
