<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StartController
{
    /**
     * @Route(path="/", methods={"GET"})
     * @return JsonResponse
     */
    public function startAction(): JsonResponse
    {
        return JsonResponse::create('App Instance');
    }
}