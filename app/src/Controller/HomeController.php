<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\Homepage\HomepageService;
use App\Service;
use App\Service\SecurityService;

class HomeController extends AbstractController
{
    private $homePageService;
    private $securityService;

    public function __construct(HomepageService $homePageService, SecurityService $securityService)
    {
        $this->homePageService = $homePageService;
        $this->securityService = $securityService;
    }
    #[Route('/dashboard', name: 'app_home')]
    public function index(): Response
    {
        $data = $this->homePageService->getHomepage();
        $user = $this->securityService->getCurrentUser();
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'data' => $data,
            'user' => $user
        ]);
    }
}
