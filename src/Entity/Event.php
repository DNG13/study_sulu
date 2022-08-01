<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Event
{
    public const RESOURCE_KEY = 'events';
    public const LIST_KEY = 'events';
    public const SECURITY_CONTEXT = 'sulu.event.events';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private ?int $id = null;

    /**
     * @ORM\Column(type="string")
     */
    private ?string $name = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?DateTimeInterface $startDate = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private ?DateTimeInterface $endDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        if (!isset($this->name)) {
            return null;
        }

        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getStartDate(): ?DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(?string $startDate): self
    {
        $startDate = DateTimeImmutable::createFromFormat('Y-m-d', $startDate);
        if ($startDate and ($this->startDate?->format('d-m-Y') !== $startDate->format('d-m-Y'))) {
            $this->startDate = $startDate;
        }

        return $this;
    }

    public function getEndDate(): ?DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(?string $endDate): self
    {
        $endDate = DateTimeImmutable::createFromFormat('Y-m-d', $endDate);
        if ($endDate and ($this->endDate?->format('d-m-Y') !== $endDate->format('d-m-Y'))) {
            $this->endDate = $endDate;
        }

        return $this;
    }
}