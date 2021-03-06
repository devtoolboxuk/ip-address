<?php

namespace devtoolboxuk\internetaddress;

class IP
{

    use IpTrait;
    /**
     * @var string
     */
    public $in_addr;

    /**
     * @param string ip
     * @throws \Exception
     */
    public function __construct($ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
            throw new \Exception("Invalid IP address format");
        }
        $this->in_addr = inet_pton($ip);
    }

    public static function parse($ip)
    {

        if (is_numeric($ip)) {
            return IPv4::parse($ip);
        }

        return new self($ip);
    }

    public static function parseHex($hexIP)
    {
        if (!preg_match('/^([0-9a-fA-F]{8}|[0-9a-fA-F]{32})$/', $hexIP)) {
            throw new \Exception("Invalid hexadecimal IP address format");
        }
        return new self(inet_ntop(pack('H*', $hexIP)));
    }


    /**
     * @param string $binIP
     * @throws \Exception
     * @return IP
     */
    public static function parseBin($binIP)
    {
        if (!preg_match('/^([0-1]{32}|[0-1]{128})$/', $binIP)) {
            throw new \Exception("Invalid binary IP address format");
        }

        $in_addr = '';
        foreach (array_map('bindec', str_split($binIP, 8)) as $char) {
            $in_addr .= pack('C*', $char);
        }

        return new self(inet_ntop($in_addr));
    }

    public static function parseInAddr($inAddr)
    {
        return new self(inet_ntop($inAddr));
    }

    public static function parseRange($data)
    {
        return Network::parse($data);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return inet_ntop($this->in_addr);
    }

    /**
     * @return int
     */
    public function getMaxPrefixLength()
    {
        return $this->getVersion() === IPv4::version
            ? IPv4::length
            : IPv6::length;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        if (filter_var(inet_ntop($this->in_addr), FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            return IPv6::version;
        }

        return IPv4::version;
    }

    /**
     * @return int
     */
    public function getOctetsCount()
    {
        return $this->getVersion() === IPv4::version
            ? IPv4::octets
            : IPv6::octets;
    }

    /**
     * @return string
     */
    public function inAddr()
    {
        return $this->in_addr;
    }

    public function toHex()
    {
        return bin2hex($this->in_addr);
    }

    /**
     * @return string
     */
    public function toBin()
    {
        $binary = array();
        foreach (unpack('C*', $this->in_addr) as $char) {
            $binary[] = str_pad(decbin($char), 8, '0', STR_PAD_LEFT);
        }

        return implode($binary);
    }


    /**
     * @return string
     */
    public function toLong()
    {

        if ($this->getVersion() === IPv6::version) {
            return IPv6::getLong($this->in_addr);
        }
        return IPv4::getLong($this->in_addr);
    }

    /**
     * @param int $next
     * @return IP
     * @throws \Exception
     */
    public function next($next = 1)
    {
        if ($next < 0) {
            throw new \Exception("Number must be greater than 0");
        }

        $unpacked = unpack('C*', $this->in_addr);

        for ($i = 0; $i < $next; $i++) {
            for ($byte = count($unpacked); $byte >= 0; --$byte) {
                if ($unpacked[$byte] < 255) {
                    $unpacked[$byte]++;
                    break;
                }

                $unpacked[$byte] = 0;
            }
        }

        return new self(inet_ntop(call_user_func_array('pack', array_merge(array('C*'), $unpacked))));
    }

    /**
     * @param int $next
     * @return IP
     * @throws \Exception
     */
    public function prev($next = 1)
    {

        if ($next < 0) {
            throw new \Exception("Number must be greater than 0");
        }

        $unpacked = unpack('C*', $this->in_addr);

        for ($i = 0; $i < $next; $i++) {
            for ($byte = count($unpacked); $byte >= 0; --$byte) {
                if ($unpacked[$byte] === 0) {
                    $unpacked[$byte] = 255;
                } else {
                    $unpacked[$byte]--;
                    break;
                }
            }
        }
        return new self(inet_ntop(call_user_func_array('pack', array_merge(array('C*'), $unpacked))));
    }
}
