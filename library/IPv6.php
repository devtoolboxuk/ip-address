<?php

namespace devtoolboxuk\ipAddress;

class IPv6 extends IP
{
    const version = 'IPv6';
    const length = 128;
    const octets = 16;

    public function __construct($ip)
    {
        parent::__construct($ip);
    }

    public function getLong($ip_addr)
    {
        $long = 0;
        $octet = IPv6::octets - 1;
        foreach ($chars = unpack('C*', $ip_addr) as $char) {
            $long = bcadd($long, bcmul($char, bcpow(256, $octet--)));
        }
        return $long;
    }

}