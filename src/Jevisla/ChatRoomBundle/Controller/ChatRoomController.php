<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jevisla\ChatRoomBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ChatRoomController extends Controller
{
    public function chatRoomMainaAction()
    {
        // Initialize
        // Render template
        return $this->render('JevislaChatRoomBundle:ChatRoom:ChatRoomMaina.html.twig');
    }
}
