<?php

namespace App\Api\V1\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Api\V1\Services\TwitterService;
use Illuminate\Http\Request;

class CommunicationController extends BaseController
{
    protected $twitterService;

    public function __construct(TwitterService $twitterService)
    {
        $this->twitterService = $twitterService;
    }

    /**
     * Subscribe users to a chat bot.
     *
     * @OA\Post(
     *     path="/api/v1/subscribe/chatbot",
     *     tags={"Chat Bot"},
     *     summary="Subscribe users to a chat bot",
     *     description="Subscribe users to a chat bot.",
     *     operationId="subscribeToChatBot",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"userId"},
     *             @OA\Property(property="userId", type="integer", example="123", description="The ID of the user to subscribe to the chat bot.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function subscribeToChatBot(Request $request)
    {
        // Validate request
        $this->validate($request, [
            'userId' => 'required'
        ]);

        $response = $this->twitterService->subscribeToChatBot($request->userId);
        return response()->json($response);
    }

    /**
     * Subscribe users to a channel or chat.
     *
     * @OA\Post(
     *     path="/api/v1/subscribe/channel",
     *     tags={"Channel or Chat"},
     *     summary="Subscribe users to a channel or chat",
     *     description="Subscribe users to a channel or chat.",
     *     operationId="subscribeToChannel",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"userId", "channelListId"},
     *             @OA\Property(property="userId", type="integer", example="123", description="The ID of the user to subscribe."),
     *             @OA\Property(property="channelListId", type="integer", example="456", description="The ID of the channel or chat to subscribe to.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function subscribeToChannel(Request $request)
    {
        // Validate request
        $this->validate($request, [
            'userId' => 'required',
            'channelListId' => 'required'
        ]);

        $response = $this->twitterService->subscribeToChannel($request->channelListId, $request->userId);
        return response()->json($response);
    }

    /**
     * Send messages to subscribers.
     *
     * @OA\Post(
     *     path="/api/v1/message",
     *     tags={"Messaging"},
     *     summary="Send messages to subscribers",
     *     description="Send messages to subscribers.",
     *     operationId="sendMessageToSubscribers",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"userId", "message"},
     *             @OA\Property(property="userId", type="string", example="123", description="The ID of the user to send the message to."),
     *             @OA\Property(property="message", type="string", example="Hello world!", description="The message to be sent to subscribers.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Message sent to subscriber successfully")
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function sendMessageToSubscriber(Request $request)
    {
        $this->validate($request, [
            'userId' => 'required',
            'message' => 'required|string',
        ]);

        return $this->twitterService->message($request->message, $request->userId);
    }

    /**
     * Webhook to receive responses from messenger API.
     *
     * @OA\Post(
     *     path="/api/v1/twitter/webhook",
     *     tags={"Webhooks"},
     *     summary="Webhook to receive responses from messenger API",
     *     description="Webhook to receive responses from messenger API.",
     *     operationId="twitterWebhook",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"crc_token"},
     *             @OA\Property(property="crc_token", type="string", example="crc_token_value", description="The CRC token value.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function twitterWebhook(Request $request)
    {
        $request = request()->has('crc_token');
        return $this->twitterService->hook($request);
    }
}
