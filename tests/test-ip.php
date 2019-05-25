<?php

namespace devtoolboxuk\internetaddress;

use PHPUnit\Framework\TestCase;

class IpTest extends TestCase
{
    function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
    }

    function testConstructor()
    {
        $ipv4String = '127.0.0.1';
        $ipv6String = '2001::';
        $ipv4 = new IP($ipv4String);
        $ipv6 = new IP($ipv6String);
        $this->assertEquals(inet_pton($ipv4String), $ipv4->inAddr());
        $this->assertEquals(IPv4::version, $ipv4->getVersion());
        $this->assertEquals(IPv4::length, $ipv4->getMaxPrefixLength());
        $this->assertEquals(IPv4::octets, $ipv4->getOctetsCount());

        $this->assertEquals(inet_pton($ipv6String), $ipv6->inAddr());
        $this->assertEquals(IPv6::version, $ipv6->getVersion());
        $this->assertEquals(IPv6::length, $ipv6->getMaxPrefixLength());
        $this->assertEquals(IPv6::octets, $ipv6->getOctetsCount());
    }

    function testStaticIpv4()
    {
        $string = '192.168.1.1';
        $result = '3232235777';

        $this->assertEquals($result, IP::parse($string)->long);
        $this->assertEquals($result, IP::parse($string)->toLong());
    }

    function testIpV4Range()
    {
        $string = '192.168.1.1';
        $range = '/28';
        $rangeResult = [
            3232235776,
            3232235777,
            3232235778,
            3232235779,
            3232235780,
            3232235781,
            3232235782,
            3232235783,
            3232235784,
            3232235785,
            3232235786,
            3232235787,
            3232235788,
            3232235789,
            3232235790,
            3232235791
        ];

        $network = IP::parseRange($string . $range);
        $i = 0;
        foreach ($network as $ip) {
            $this->assertEquals($rangeResult[$i], IP::parse($ip)->long);
            $i++;
        }
    }

    function testNonStaticIpv4()
    {
        $string = '192.168.1.1';
        $result = '3232235777';
        $ip = new IP($string);

        $this->assertEquals($result, $ip->long);
        $this->assertEquals($result, $ip->toLong());

    }

}
