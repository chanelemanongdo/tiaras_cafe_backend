<?php

namespace App\Controllers;

use App\Repositories\ReviewRepository;
use App\Models\Review;

class ReviewController
{
    private array $reviews = [];

    public function loadReviewsFromDatabase(ReviewRepository $repository): void
    {
        $this->reviews = $repository->getAllReviews();
    }

    public function saveReview(
    Review $review,
    ReviewRepository $repository
    ): bool {
        if (!$this->isValidRating($review->getRating())) {
            return false;
        }

        if (!$repository->addReview($review)) {
            return false;
        }

        $this->loadReviewsFromDatabase($repository);

        return true;
    }

    public function deleteReviewFromDatabase(
        int $reviewId,
        ReviewRepository $repository
    ): bool {
        if (!$repository->deleteReview($reviewId)) {
            return false;
        }

        $this->loadReviewsFromDatabase($repository);

        return true;
    }

    public function addReview(Review $review): bool
    {
        if (!$this->isValidRating($review->getRating())) {
            return false;
        }

        $this->reviews[] = $review;
        return true;
    }

    public function isValidRating(int $rating): bool
    {
        return $rating >= 1 && $rating <= 5;
    }

    public function getAllReviews(): array
    {
        return $this->reviews;
    }

    public function sortReviewsByRating(): array
    {
        $sortedReviews = $this->reviews;

        for ($i = 1; $i < count($sortedReviews); $i++) {
            $currentReview = $sortedReviews[$i];
            $j = $i - 1;

            while (
                $j >= 0 &&
                $sortedReviews[$j]->getRating() < $currentReview->getRating()
            ) {
                $sortedReviews[$j + 1] = $sortedReviews[$j];
                $j--;
            }

            $sortedReviews[$j + 1] = $currentReview;
        }

        return $sortedReviews;
    }

    public function deleteReview(int $index): void
    {
        if (isset($this->reviews[$index])) {
            unset($this->reviews[$index]);
            $this->reviews = array_values($this->reviews);
        }
    }
}