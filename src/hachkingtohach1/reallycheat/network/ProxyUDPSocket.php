<?php

namespace hachkingtohach1\reallycheat\network;

use hachkingtohach1\reallycheat\RCAPIProvider;
use hachkingtohach1\reallycheat\utils\InternetAddress;

class ProxyUDPSocket {

    protected $socket;
    protected InternetAddress $bindAddress;

    public function __construct(){
        $this->socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
        socket_set_option($this->socket, SOL_SOCKET, SO_SNDBUF, 1024 * 1024 * 8);
        socket_set_option($this->socket, SOL_SOCKET, SO_RCVBUF, 1024 * 1024 * 8);
    }

    public function bind(InternetAddress $address){
        if(socket_bind($this->socket, $address->ip, $address->port)){
            RCAPIProvider::getInstance()->getLogger()->info("Successfully bound to {$address->ip}:{$address->port}");
            $result = socket_connect($this->socket, $address->ip, $address->port);        
            if($result){               
                RCAPIProvider::getInstance()->getLogger()->info("Successfully connected to {$address->ip}:{$address->port}");                               
            }
        }else{
            throw new \Exception("Could not bound to {$address->ip}:{$address->port}");
        }
    }

    public function receive(?string &$buffer, ?string &$ip, ?int &$port){
        socket_recvfrom($this->socket, $buffer, 65535, 0, $ip, $port);
    }

    public function send(string $buffer, string $ip, int $port){
        socket_sendto($this->socket, $buffer, strlen($buffer), 0, $ip, $port);
    }

    public function close(){
        socket_close($this->socket);
    }
    
}