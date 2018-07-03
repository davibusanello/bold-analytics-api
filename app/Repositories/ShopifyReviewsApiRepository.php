<?php

namespace App\Repositories;
use App\Reviews;
use Carbon\Carbon;


class ShopifyReviewsApiRepository
{
    /**
     * @var string
     */
    private $shopifyUrl;

    /**
     * @var string
     */
    private $appName;

    /**
     * @var int
     */
    private $totalCreated = 0;

    /**
     * @var int
     */
    private $totalUpdated = 0;

    /**
     * ShopifyReviewsApiRepository constructor.
     * @param string $base_url
     */
    public function __construct($base_url)
    {
        $this->shopifyUrl = $base_url;
    }


    public function getReviews($appName)
    {
        $this->appName = $appName;
        $url = "{$this->shopifyUrl}/{$appName}/reviews.json";
        $reviewsJson = $this->getJson($url);
        $reviews = collect($reviewsJson->reviews);
        foreach ($reviews as $review) {
            $this->syncReview($review);
        }
    }

    /**
     * Get totals of reviews synced
     * @return array
     */
    public function getSyncTotal()
    {
        return [
            'totalCreated' => $this->totalCreated,
            'totalUpdated' => $this->totalUpdated,
        ];
    }

    /**
     * Fetch the api
     * @param string $url
     * @return mixed
     */
    protected function getJson($url)
    {
        $response = file_get_contents($url, false);
        return json_decode( $response );
    }

    protected function syncReview($apiReview)
    {
        $currentReview = Reviews::where('app_name', $this->appName)
                                ->where('shop_domain', $apiReview->shop_domain)->first();

        if (!$currentReview) {
            return $this->createReview($apiReview);
        }


        $currentReviewDate = $currentReview->created_at->format("Y-m-d H:i:s");
        $apiReviewDate = Carbon::parse($apiReview->created_at)->format("Y-m-d H:i:s");

        if ($currentReviewDate < $apiReviewDate
            || $currentReview->star_rating != $apiReview->star_rating
            || $currentReview->body != $apiReview->body) {

            return $this->updateReview($currentReview, $apiReview);
        }
    }

    /**
     * @param mixed $data
     * @return Reviews
     */
    protected function createReview($data)
    {
        $review = new Reviews();
        $review->app_name = $this->appName;
        $review->author = $data->author;
        $review->body = $data->body;
        $review->created_at = Carbon::parse($data->created_at);
        $review->shop_name = $data->shop_name;
        $review->shop_domain = $data->shop_domain;
        $review->star_rating = $data->star_rating;
        $review->save();
        $this->totalCreated++;
        return $review;
    }


    protected function updateReview($review, $data)
    {
        $review->author = $data->author;
        if ($review->body != $data->body) {
            $review->body = $data->body;
        }
        if ($review->shop_name != $data->shop_name) {
            $review->shop_name = $data->shop_name;
        }
        $review->previous_star_rating = $review->star_rating;
        $review->star_rating = $data->star_rating;
        $review->created_at = Carbon::parse($data->created_at);
        $review->save();

        $this->totalUpdated++;
        return $review;

    }


}