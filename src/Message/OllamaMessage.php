<?php

namespace App\Message;

final class OllamaMessage
{
     public function __construct(
         public int $ollamaRequestId,
     ) {
     }
}
