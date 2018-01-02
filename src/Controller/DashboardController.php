<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class DashboardController extends Controller
{
    /**
     * @Route("/dashboard", name="dashboard")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render(':Dashboard:index.html.twig');
    }
}
