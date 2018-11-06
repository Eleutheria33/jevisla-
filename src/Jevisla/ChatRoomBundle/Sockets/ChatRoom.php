<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// myapp\src\yourBundle\Sockets\Chat.php;

// Change the namespace according to your bundle, and that's all !

namespace Jevisla\ChatRoomBundle\Sockets;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatRoom implements MessageComponentInterface
{
    /**
     * @var \SplObjectStorage
     */
    private $clients;

    /**
     * @var array
     */
    private $users = [];

    /**
     * @var array
     */
    private $channels = [];

    /**
     * @var string
     */
    private $botName = 'ChatBot';

    /**
     * @var string
     */
    private $defaultChannel = 'general';

    /**
     * ChatRoom constructor.
     */
    public function __construct()
    {
        $this->clients = new \SplObjectStorage();
        $this->users = [];
        $this->channels = [];
    }

    /**
     * A new websocket connection.
     *
     * @param ConnectionInterface $conn
     */
    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        // store into the list the new user connected
        $this->users[$conn->resourceId] = [
            'connection' => $conn,
            'user' => '',
            'channels' => [],
        ];

        echo "New connection! ({$conn->resourceId})\n";
    }

    /**
     * A connection is closed.
     *
     * @param ConnectionInterface $conn
     */
    public function onClose(ConnectionInterface $conn)
    {
        // envoi message d'aurevoir
        /*$this->sendMessageToChannel(
            $conn,
            $this->defaultChannel,
            $this->botName,
            'id',
            'URL non mais',
            $this->users[$conn->resourceId]['user'].' has disconnected'

        );*/
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);
        unset($this->users[$conn->resourceId]);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    /**
     * Error handling.
     *
     * @param ConnectionInterface $conn
     * @param \Exception          $e
     */
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->send(
            json_encode(
                [
                'action' => 'message',
                'channel' => $this->defaultChannel,
                'user' => $this->botName,
                'message' => 'An error has occurred: '.$e->getMessage(),
                ]
            )
        );
        $conn->close();
    }

    /**
     * Handle message sending.
     *
     * @param ConnectionInterface $from
     * @param string              $msg
     *
     * @return bool - False if message is not a valid JSON or action is invalid
     */
    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;

        $messageData = json_decode($msg);
        if (null === $messageData) {
            return false;
        }
        $action = $messageData->action ?? 'unknown';
        $channel = $messageData->channel ?? $this->defaultChannel;
        $user = $messageData->user ?? $this->botName;
        $userId = $messageData->id ?? $this->botName;
        $url = $messageData->url ?? $this->botName;
        $message = $messageData->message ?? '';

        switch ($action) {
        case 'createChannel':
            $this->createToChannel($channel);

            return true;
        case 'subscribe':
            $this->subscribeToChannel($from, $channel, $user, $userId, $url);

            return true;
        case 'unsubscribe':
            $this->unsubscribeFromChannel($from, $channel, $user, $userId, $url);

            return true;
        case 'message':
            return $this->sendMessageToChannel($from, $channel, $user, $userId, $url, $message);

        default:
            echo sprintf('Action "%s" is not supported yet!', $action);
            break;
        }
        // Return error
        return false;
    }

    public function getChannels(ConnectionInterface $from)
    {
        if (!isset($this->users[$from->resourceId]['channels'])) {
            return false;
        }

        foreach ($this->users as $connectionId => $userConnection) {
            if ($connectionId !== $from) {
                // The sender is not the receiver, send to each client connected
                //$client->send($message);

                if (array_key_exists($channel, $userConnection['channels'])) {
                    $userConnection['connection']->send(
                        json_encode(
                            [
                            'action' => 'message',
                            'channel' => $channel,
                            'username' => $user,
                            'message' => $message,
                            'id' => $userId,
                            'url' => $url,
                            ]
                        )
                    );
                }
            }
        }

        return true;
    }

    public function getUserChannel(ConnectionInterface $from, $channel, $user, $userId, $url, $message)
    {
        if (!isset($this->users[$from->resourceId]['channels'][$channel])) {
            return false;
        }

        foreach ($this->users as $connectionId => $userConnection) {
            if ($connectionId !== $from) {
                // The sender is not the receiver, send to each client connected
                //$client->send($message);

                if (array_key_exists($channel, $userConnection['channels'])) {
                    $userConnection['connection']->send(
                        json_encode(
                            [
                            'action' => 'message',
                            'channel' => $channel,
                            'username' => $user,
                            'message' => $message,
                            'id' => $userId,
                            'url' => $url,
                            ]
                        )
                    );
                }
            }
        }

        return true;
    }

    /**
     * Subscribe connection to a given channel.
     *
     * @param ConnectionInterface $conn - Active connection
     * @param $channel - Channel to subscribe to
     * @param $user - Username of subscribed user
     */
    private function subscribeToChannel(ConnectionInterface $conn, $channel, $user, $userId, $url)
    {
        $this->users[$conn->resourceId]['channels'][$channel] = $channel;
        $this->sendMessageToChannel(
            $conn,
            $channel,
            $user,
            $userId,
            $url,
            $user.' a rejoint #'.$channel
        );
    }

    /**
     * create channel to a given channel.
     *
     * @param ConnectionInterface $conn - Active connection
     * @param $channel - Channel to subscribe to
     * @param $user - Username of subscribed user
     */
    private function createToChannel($channel)
    {
        $this->channels[$channel] = $channel;
    }

    /**
     * Unsubscribe connection to a given channel.
     *
     * @param ConnectionInterface $conn - Active connection
     * @param $channel - Channel to unsubscribe from
     * @param $user - Username of unsubscribed user
     */
    private function unsubscribeFromChannel(ConnectionInterface $conn, $channel, $user, $userId, $url)
    {
        if (array_key_exists($channel, $this->users[$conn->resourceId]['channels'])) {
            unset($this->users[$conn->resourceId]['channels']);
        }
        $this->sendMessageToChannel(
            $conn,
            $channel,
            $this->botName,
            $userId,
            $url,
            $user.' left #'.$channel
        );
    }

    /**
     * Send message to all connections of a given channel.
     *
     * @param ConnectionInterface $conn - Active connection
     * @param $channel - Channel to send message to
     * @param $user - User's username
     * @param $message - User's message
     *
     * @return bool - False if channel doesn't exists
     */
    private function sendMessageToChannel(ConnectionInterface $from, $channel, $user, $userId, $url, $message)
    {
        if (!array_key_exists($channel, $this->channels)) {
            $this->createToChannel($channel);
        }
        if (!isset($this->users[$from->resourceId]['channels'][$channel])) {
            $this->subscribeToChannel($from, $channel, $user, $userId, $url);
        }

        foreach ($this->users as $connectionId => $userConnection) {
            if ($connectionId !== $from) {
                // The sender is not the receiver, send to each client connected
                //$client->send($message);

                if (array_key_exists($channel, $userConnection['channels'])) {
                    $userConnection['connection']->send(
                        json_encode(
                            [
                            'action' => 'message',
                            'channel' => $channel,
                            'username' => $user,
                            'message' => $message,
                            'id' => $userId,
                            'url' => $url,
                            'channels' => $this->channels,
                            'users' => $this->users,
                            ]
                        )
                    );
                }
            }
        }

        return true;
    }
}
