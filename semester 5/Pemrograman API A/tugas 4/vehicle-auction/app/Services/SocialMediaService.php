<?php

namespace App\Services;

use App\Models\Auction;
use App\Models\SocialAccount;
use App\Models\SocialPost;
use Illuminate\Support\Facades\Http;

class SocialMediaService
{
    public function postToFacebook(Auction $auction, SocialAccount $account): SocialPost
    {
        $vehicle = $auction->vehicle;
        $content = $this->formatPostContent($auction);
        $images = $vehicle->images->pluck('image_path')->toArray();

        $post = SocialPost::create([
            'auction_id' => $auction->id,
            'social_account_id' => $account->id,
            'platform' => 'facebook',
            'content' => $content,
            'media_urls' => $images,
            'status' => 'pending',
        ]);

        try {
            $endpoint = $account->group_id 
                ? "https://graph.facebook. com/{$account->group_id}/feed"
                : "https://graph.facebook.com/{$account->page_id}/feed";

            $response = Http::post($endpoint, [
                'message' => $content,
                