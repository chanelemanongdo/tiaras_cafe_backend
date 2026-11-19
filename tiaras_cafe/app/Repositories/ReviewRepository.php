<?php

namespace App\Repositories;

use App\Models\Review;
use PDO;

class ReviewRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAllReviews(): array
    {
        $sql = "
            SELECT review_id, email_address, rating, comment, created_at
            FROM review
            ORDER BY created_at DESC
        ";

        $statement = $this->connection->query($sql);
        $rows = $statement->fetchAll();

        $reviews = [];

        foreach ($rows as $row) {
            $reviews[] = new Review(
                $row['email_address'],
                (int) $row['rating'],
                $row['comment'],
                (int) $row['review_id'],
                $row['created_at']
            );
        }

        return $reviews;
    }

    public function addReview(Review $review): bool
    {
        $sql = "
            INSERT INTO review (email_address, rating, comment, created_at)
            VALUES (:email, :rating, :comment, NOW())
        ";

        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            ':email' => $review->getEmailAddress(),
            ':rating' => $review->getRating(),
            ':comment' => $review->getComment()
        ]);
    }

    public function deleteReview(int $reviewId): bool
    {
        $sql = "DELETE FROM review WHERE review_id = :id";

        $statement = $this->connection->prepare($sql);

        return $statement->execute([
            ':id' => $reviewId
        ]);
    }
}
