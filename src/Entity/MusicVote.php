<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\MusicVoteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MusicVoteRepository::class)]
#[ApiResource]
class MusicVote
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $musicName;

    #[ORM\Column(type: 'integer')]
    private $vote;

    #[ORM\Column(type: 'integer')]
    private $codeRound;

    #[ORM\Column(type: 'string', length: 255)]
    private $uri;

    #[ORM\ManyToOne(targetEntity: Room::class, inversedBy: 'musicVotes')]
    #[ORM\JoinColumn(nullable: false)]
    private $room;

    #[ORM\Column(type: 'string', length: 255)]
    private $idMusic;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMusicName(): ?string
    {
        return $this->musicName;
    }

    public function setMusicName(string $musicName): self
    {
        $this->musicName = $musicName;

        return $this;
    }

    public function getVote(): ?int
    {
        return $this->vote;
    }

    public function setVote(int $vote): self
    {
        $this->vote = $vote;

        return $this;
    }

    public function getCodeRound(): ?int
    {
        return $this->codeRound;
    }

    public function setCodeRound(int $codeRound): self
    {
        $this->codeRound = $codeRound;

        return $this;
    }

    public function getUri(): ?string
    {
        return $this->uri;
    }

    public function setUri(string $uri): self
    {
        $this->uri = $uri;

        return $this;
    }

    public function getRoom(): ?Room
    {
        return $this->room;
    }

    public function setRoom(?Room $room): self
    {
        $this->room = $room;

        return $this;
    }

    public function getIdMusic(): ?string
    {
        return $this->idMusic;
    }

    public function setIdMusic(string $idMusic): self
    {
        $this->idMusic = $idMusic;

        return $this;
    }
}
