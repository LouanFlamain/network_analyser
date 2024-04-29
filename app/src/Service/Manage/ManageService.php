<?php

namespace App\Service\Manage;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Device;
class ManageService {
    
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }
    public function getManage(){
        $devices = $this->entityManager->getRepository(Device::class)->findAll();
        if($devices){
            return $devices;
        }
        return $devices;
    }
    public function getOneItem($addr_mac){
        $device = $this->entityManager->getRepository(Device::class)->findOneBy(['mac' => $addr_mac]);
        if($device){
            return $device;
        }
    }
    public function changeFavoris($addr_mac){
        $device = $this->entityManager->getRepository(Device::class)->findOneBy(['mac' => $addr_mac]);
        if($device !== null){
            $currentState = $device->isFavoris();
            $device->setFavoris(!$currentState);
        }
        $this->entityManager->flush();
    }
    public function changePseudo($addr_mac, $pseudo){
        if(!empty($pseudo)){
            $device = $this->entityManager->getRepository(Device::class)->findOneBy(['mac' => $addr_mac]);
            if($device !== null){
                $device->setPseudo($pseudo);
            }
            $this->entityManager->flush();
        }
    }
}