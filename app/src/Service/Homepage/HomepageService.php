<?php

namespace App\Service\Homepage;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Device;

class HomepageService {
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    public function getHomepage(){
        $devices = $this->entityManager->getRepository(Device::class)->findBy(['favoris' => true]);
        $response = [];
        if($devices){
            $response = $devices;
        }
        return $response;
    }
}
?>