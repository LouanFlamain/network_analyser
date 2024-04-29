<?php

namespace App\Service;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class SecurityService{

    private $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage){
        $this->tokenStorage = $tokenStorage;
    }
    public function getCurrentUser(){
        $token = $this->tokenStorage->getToken();
        if($token !== null){
            return $token->getUser();
        }
        return null;
    }
}