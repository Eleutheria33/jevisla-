<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jevisla\ChatRoomBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
// Include ratchet libs
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
// Change the namespace according to your bundle
use Jevisla\ChatRoomBundle\Sockets\ChatRoom;

class SocketCommand extends Command
{
    protected function configure()
    {
        $this->setName('sockets:start-chatroom')
            // the short description shown while running "php bin/console list"
            ->setHelp('Starts the chat socket demo')
            // the full command description shown when running the command with
            ->setDescription('Starts the chat socket demo');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(
            [
            'Chat socket', // A line
            '============', // Another line
            'Starting chatRoom, open your browser.', // Empty line
            ]
        );

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new ChatRoom()
                )
            ),
            8705
        );

        $server->run();
    }
}
