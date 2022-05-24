<?php

namespace App\Events;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;
class JwtCreatedSubscriber
{
    public function updateJwtData(JWTCreatedEvent $event)
    {

        // 1 . Récuperer l'utilisateur pour avoir son firstname et lastname
        $user = $event->getUser();

        //2. Enrichier les data pour quelles contiennent ces données
        $data = $event->getData();
        $data['firstName'] = $user->getFirstName();
        $data['lastName'] = $user->getLastName();

        $event->setData($data);
    }
}