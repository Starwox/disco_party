<?php

namespace App\Entity;

use App\Repository\RoomRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Core\Annotation\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: RoomRepository::class)]
#[ApiResource(
    order: ['id' => 'ASC'],
   paginationEnabled: false
)]
class Room
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    #[Groups(['room:list', 'room:item'])]
    private $id;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['room:list', 'room:item'])]
    private $name;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    #[Groups(['room:list', 'room:item'])]
    private $code;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private $lastUpdate;

    #[ORM\Column(type: 'boolean')]
    private $enable;

    #[ORM\OneToMany(mappedBy: 'room', targetEntity: MusicVote::class, orphanRemoval: true)]
    private $musicVotes;

    public function __construct()
    {
        $this->musicVotes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getLastUpdate(): ?\DateTimeInterface
    {
        return $this->lastUpdate;
    }

    public function setLastUpdate(?\DateTimeInterface $lastUpdate): self
    {
        $this->lastUpdate = $lastUpdate;

        return $this;
    }

    public function getEnable(): ?bool
    {
        return $this->enable;
    }

    public function setEnable(bool $enable): self
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * @return Collection<int, MusicVote>
     */
    public function getMusicVotes(): Collection
    {
        return $this->musicVotes;
    }

    public function addMusicVote(MusicVote $musicVote): self
    {
        if (!$this->musicVotes->contains($musicVote)) {
            $this->musicVotes[] = $musicVote;
            $musicVote->setRoom($this);
        }

        return $this;
    }

    public function removeMusicVote(MusicVote $musicVote): self
    {
        if ($this->musicVotes->removeElement($musicVote)) {
            // set the owning side to null (unless already changed)
            if ($musicVote->getRoom() === $this) {
                $musicVote->setRoom(null);
            }
        }

        return $this;
    }
}
