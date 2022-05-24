<?php

namespace App\Service;

use Symfony\Component\Mailer\Mailer;
use Symfony\Contracts\Translation\TranslatorInterface;

class MessageGenerator
{
    private $translator;
    public function __construct(TranslatorInterface $translator)
    {
       $this->translator = $translator;
    }
    public function getHappyMessage(): string
    {
        $messages = [
            'You did it! You updated the system! Amazing!',
            'That was one of the coolest updates I\'ve seen all day!',
            'Great work! Keep going!',
        ];

        $index = array_rand($messages);
        
        return $this->translator->trans($messages[$index]);
    }
}