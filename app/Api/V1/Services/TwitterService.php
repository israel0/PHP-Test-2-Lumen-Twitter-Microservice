<?php

namespace App\Api\V1\Services;

use Atymic\Twitter\Exception\ClientException;
use Atymic\Twitter\Facade\Twitter;
use Illuminate\Support\Facades\Log;

class TwitterService
{
    protected $twitter;

    public function __construct()
    {
        $twitterConfig = config('services.twitter');

        // Initialize Twitter SDK with credentials
        $this->twitter = new Twitter([
            'consumer_key' => $twitterConfig['consumer_key'],
            'consumer_secret' => $twitterConfig['consumer_secret'],
            'access_token' => $twitterConfig['access_token'],
            'access_token_secret' => $twitterConfig['access_token_secret'],
        ]);
    }

    // Add user to a chatlist or bot
    public function subscribeToChatBot($userId)
    {
        try {
            $response = $this->twitter->postFollow(['user_id' => $userId]);
            return $response;
        } catch (ClientException $e) {
            Log::error('Client exception occurred: ' . $e->getMessage());
            return ['error' => 'Client exception occurred.'];
        }catch (\Exception $e) {
            Log::error('Unexpected exception occurred: ' . $e->getMessage());
            return ['error' => 'Unexpected exception occurred.'];
        }
    }

    // add user to chatlist or channel
    public function subscribeToChannel($channelListId, $userId)
    {
        try {
            $response = $this->twitter->postListSubscriber(['list_id' => $channelListId, 'user_id' => $userId]);
            return $response;
        } catch (ClientException $e) {
            Log::error('Client exception occurred: ' . $e->getMessage());
            return ['error' => 'Client exception occurred.'];
        } catch (\Exception $e) {
            Log::error('Unexpected exception occurred: ' . $e->getMessage());
            return ['error' => 'Unexpected exception occurred.'];
        }
    }

    // send message to user
    public function message($message, $userId)
    {
        try {
            $response = $this->twitter->postDm(['text' => $message, 'recipient_id' => $userId]);
            if ($response) {
                return response()->json(['message' => 'Message sent to subscriber successfully']);
            }
        } catch (ClientException $e) {
            Log::error('Client exception occurred: ' . $e->getMessage());
            return ['error' => 'Client exception occurred.'];
        } catch (\Exception $e) {
            Log::error('Unexpected exception occurred: ' . $e->getMessage());
            return ['error' => 'Unexpected exception occurred.'];
        }
    }

    public function hook($request) {
        if ($request)
        return response()->json(['response_token' => Twitter::crcHash(request()->crc_token)], 200);

    }
}
