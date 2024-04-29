<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Device;

class ReadXmlCommandService {

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createOrUpdateDevice($data){
        $device = $this->entityManager->getRepository(Device::class)->findOneBy(['mac' => $data['addr_mac']]);
        if($device){
            $name = $device->getName();
            $addr_ip = $device->getIp();
            $state = $device->isState();
            if($name !== $data['device_name']){
                $device->setName($data['device_name']);
            }
            if($addr_ip !== $data['addr_ip']){
                $device->setIp($data['addr_ip']);
            }
            if(!$state){
                $device->setState(true);
            }
            $device->setTimestamp($data['timestamp']);
        }else{
            $device = new Device();
            $device->setName($data['device_name'])
            ->setIp($data['addr_ip'])
            ->setMac($data['addr_mac'])
            ->setTimestamp($data['timestamp'])
            ->setFavoris(false)
            ->setState(true);
            $this->entityManager->persist($device);
        }
        $this->entityManager->flush();
    }
    public function disableNonPresenceDevice($addr_mac){
        $devices = $this->entityManager->getRepository(Device::class)->findAll();
    
        if ($devices) {
            foreach ($devices as $device) {
                $device_mac_addr = $device->getMac();
                $is_recognized = false;
                foreach ($addr_mac as $addr) {
                    if ($device_mac_addr === $addr) {
                        $is_recognized = true;
                        break;
                    }
                }
                if (!$is_recognized) {
                    $device->setState(false);
                }
            }
            $this->entityManager->flush();
        }
    }
    
}