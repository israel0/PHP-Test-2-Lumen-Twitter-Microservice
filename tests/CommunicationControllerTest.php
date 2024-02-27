<?php 

namespace Tests;

use Tests\TestCase;

class CommunicationControllerTest extends TestCase
{
    /**
     * Test subscribeToChatBot endpoint.
     */
    public function testSubscribeToChatBot()
    {
        $response = $this->json('POST', '/api/v1/subscribe/chatbot', ['userId' => 123]);
 
            //  response->assertStatus(200)
            //      ->assertJson([
            //          "success" => true,
            //          "message" => "ChatBot Subscription Successful"
            //      ]);

                
               
    }

    /**
     * Test subscribeToChannel endpoint.
     */
    public function testSubscribeToChannel()
    {
        $response = $this->json('POST', '/api/v1/subscribe/channel', ['userId' => 123, 'channelListId' => 456]);

        // $response->assertStatus(200)
        //          ->assertJson([
        //             "success" => true,
        //              "message" => "Channel Subscription Successful"
        //          ]);
    }

    /**
     * Test sendMessageToSubscriber endpoint.
     */
    public function testSendMessageToSubscriber()
    {
        $response = $this->json('POST', '/api/v1/message', ['userId' => 123, 'message' => 'Hello']);

        // $response->assertStatus(200)
        //          ->assertJson([
        //             "success" => true,
        //             "message" => "Message Successful"
        //          ]);
    }

    /**
     * Test twitterWebhook endpoint.
     */
    public function testTwitterWebhook()
    {
        $response = $this->json('POST', '/api/v1/twitter/webhook', ['crc_token' => 'crc_token_value']);
        $response->assertStatus(200);
    }
}
