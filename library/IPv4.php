<?php

namespace devtoolboxuk\ipAddress;

class IPv4 extends IP
{
    const version = 'IPv4';
    const length = 32;
    const octets = 4;

    public function __construct($ip)
    {
        parent::__construct($ip);
    }

    public static function parse($ipAddress)
    {
        return new self(long2ip($ipAddress));
    }

    public static function getLong($ip_addr)
    {
        return sprintf('%u', ip2long(inet_ntop($ip_addr)));
    }

}