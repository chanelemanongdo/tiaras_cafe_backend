<?php

namespace App\Models;

class Review
{
    private ?int $reviewId;
    private string $emailAddress;
    private int $rating;
    private string $comment;
    private ?string $createdAt;

    public function __construct(
        string $emailAddress,
        int $rating,
        string $comment,
        ?int $reviewId = null,
        ?string $createdAt = null
    ) {
        $this->reviewId = $reviewId;
        $this->emailAddress = $emailAddress;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->createdAt = $createdAt;
    }

    public function getReviewId(): ?int { return $this->reviewId; }
    public function getEmailAddress(): string { return $this->emailAddress; }
    public function getRating(): int { return $this->rating; }
    public function getComment(): string { return $this->comment; }
    public function getCreatedAt(): ?string { return $this->createdAt; }

    public function setRating(int $rating): void { $this->rating = $rating; }
    public function setComment(string $comment): void { $this->comment = $comment; }
}