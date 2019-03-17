<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TaskRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Task
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @SWG\Property(property="name")
     * @SerializedName("name")
     * @Groups({ "upsert_dto" })
     */
    private $Name;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @SWG\Property(property="description")
     * @SerializedName("description")
     * @Groups({ "upsert_dto" })
     */
    private $Description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @SWG\Property(property="dueDate")
     * @SerializedName("dueDate")
     * @Groups({ "upsert_dto" })
     */
    private $DueDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @SWG\Property(property="user")
     * @SerializedName("assignedTo")
     * @Groups({ "upsert_dto" })
     */
    private $AssignedTo;

    /**
     * @var \DateTime
     * @SerializedName("created_at")
     * @ORM\Column(type="datetime")
     */
    private $CreatedAt;

    /**
     * @var \DateTime
     * @SerializedName("updated_at")
     * @ORM\Column(type="datetime")
     */
    private $UpdatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(?string $Description): void
    {
        $this->Description = $Description;
    }

    public function getDueDate(): ?\DateTimeInterface
    {
        return $this->DueDate;
    }

    public function setDueDate(\DateTimeInterface $DueDate): void
    {
        $this->DueDate = $DueDate;
    }

    public function getAssignedTo(): ?string
    {
        return $this->AssignedTo;
    }

    public function setAssignedTo(?string $AssignedTo): void
    {
        $this->AssignedTo = $AssignedTo;
    }

    /**
     * Get the value of createdAt
     *
     * @return  \DateTime
     */
    public function getCreatedAt()
    {
        return $this->CreatedAt;
    }

    /**
     * Set the value of createdAt
     *
     * @param  \DateTime  $createdAt
     *
     * @return  self
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->CreatedAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->UpdatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->UpdatedAt = $updatedAt;
    }

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }
}
