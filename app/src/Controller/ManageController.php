<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\Manage\ManageService;

class ManageController extends AbstractController
{
    private $manageService;
    public function __construct(ManageService $manageService){
        $this->manageService = $manageService;
    }
    #[Route('/manage', name: 'app_manage')]
    public function index(): Response
    {
        $data = $this->manageService->getManage();
        return $this->render('manage/index.html.twig', [
            'controller_name' => 'ManageController',
            'data' => $data
        ]);
    }
    #[Route('/manage/{slug}', name: "app_manage_item")]
    public function manageItem(string $slug) : Response{
        $data = $this->manageService->getOneItem($slug);
        return $this->render('manage_item/index.html.twig', [
            'controller_name' => 'ManageController',
            'data' => $data
        ]);
    }
    #[Route('/manage/favoris/{slug}', name: "app_manage_state")]
    public function changeState(string $slug) : Response{
        if (!$slug ) {
            return new Response('No MAC address provided', 400);
        }
        $this->manageService->changeFavoris($slug);
        return $this->redirectToRoute("app_manage");
    }
    #[Route('/manage/pseudo/{slug}', name: "app_manage_pseudo")]
    public function changePseudo(Request $request, string $slug) : Response{
        $pseudo = $request->request->get('pseudo');
        if (!$slug | !$pseudo ) {
            return new Response('No MAC address provided', 400);
        }
        $this->manageService->changePseudo($slug, $pseudo);
        return $this->redirectToRoute("app_manage");
    }
}
