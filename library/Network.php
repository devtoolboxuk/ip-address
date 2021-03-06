<?php

namespace devtoolboxuk\internetaddress;

class Network implements \Iterator
{
    use IpTrait;
    /**
     * @var IP
     */
    private $ip;
    /**
     * @var IP
     */
    private $netmask;
    /**
     * @var int
     */
    private $position = 0;


    public function __construct(IP $ip, IP $netmask)
    {
        $this->setIP($ip);
        $this->setNetmask($netmask);
    }

    /**
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getCIDR();
    }

    public function getWildcard()
    {
        return new IP(inet_ntop(~$this->getNetmask()->inAddr()));
    }

    /**
     * @return IP
     */
    public function getNetmask()
    {
        return $this->netmask;
    }

    /**
     * @param IP ip
     * @throws \Exception
     */
    public function setNetmask(IP $ip)
    {
        if (!preg_match('/^1*0*$/', $ip->toBin())) {
            throw new \Exception('Invalid Netmask address format');
        }

        if (isset($this->ip) && $ip->getVersion() !== $this->ip->getVersion()) {
            throw new \Exception('Netmask version is not same as IP version');
        }

        $this->netmask = $ip;
    }

    /**
     * @return Range
     * @throws \Exception
     */
    public function getHosts()
    {
        $firstHost = $this->getNetwork();
        $lastHost = $this->getBroadcast();

        if ($this->ip->getVersion() === IPv4::version) {
            if ($this->getBlockSize() > 2) {
                $firstHost = IP::parseBin(substr($firstHost->toBin(), 0, $firstHost->getMaxPrefixLength() - 1) . '1');
                $lastHost = IP::parseBin(substr($lastHost->toBin(), 0, $lastHost->getMaxPrefixLength() - 1) . '0');
            }
        }

        return new Range($firstHost, $lastHost);
    }

    public function getNetwork()
    {
        return new IP(inet_ntop($this->getIP()->inAddr() & $this->getNetmask()->inAddr()));
    }

    /**
     * @return IP
     */
    public function getIP()
    {
        return $this->ip;
    }

    /**
     * @param IP ip
     * @throws \Exception
     */
    public function setIP(IP $ip)
    {
        if (isset($this->netmask) && $this->netmask->getVersion() !== $ip->getVersion()) {
            throw new \Exception('IP version is not same as Netmask version');
        }

        $this->ip = $ip;
    }

    /**
     * @return IP
     * @throws \Exception
     */
    public function getBroadcast()
    {
        return new IP(inet_ntop($this->getNetwork()->inAddr() | ~$this->getNetmask()->inAddr()));
    }

    /**
     * @return int|string
     */
    public function getBlockSize()
    {
        $maxPrefixLength = $this->ip->getMaxPrefixLength();
        $prefixLength = $this->getPrefixLength();

        if ($this->ip->getVersion() === IP::v6) {
            return bcpow('2', (string)($maxPrefixLength - $prefixLength));
        }

        return pow(2, $maxPrefixLength - $prefixLength);
    }

    /**
     * @return int
     */
    public function getPrefixLength()
    {
        return self::netmask2prefix($this->getNetmask());
    }

    /**
     * @param IP ip
     * @return int
     */
    public function netmask2prefix(IP $ip)
    {
        return strlen(rtrim($ip->toBin(), 0));
    }

    /**
     * @param IP|Network $exclude
     * @return Network[]
     * @throws \Exception
     */
    public function exclude($exclude)
    {
        $exclude = self::parse($exclude);

        if (strcmp($exclude->getFirstIP()->inAddr(), $this->getLastIP()->inAddr()) > 0
            || strcmp($exclude->getLastIP()->inAddr(), $this->getFirstIP()->inAddr()) < 0
        ) {
            throw new \Exception('Exclude subnet not within target network');
        }

        $networks = array();

        $newPrefixLength = $this->getPrefixLength() + 1;
        if ($newPrefixLength > $this->ip->getMaxPrefixLength()) {
            return $networks;
        }

        $lower = clone $this;
        $lower->setPrefixLength($newPrefixLength);

        $upper = clone $lower;
        $upper->setIP($lower->getLastIP()->next());

        while ($newPrefixLength <= $exclude->getPrefixLength()) {
            $range = new Range($lower->getFirstIP(), $lower->getLastIP());
            if ($range->contains($exclude)) {
                $matched = $lower;
                $unmatched = $upper;
            } else {
                $matched = $upper;
                $unmatched = $lower;
            }

            $networks[] = clone $unmatched;

            if (++$newPrefixLength > $this->getNetwork()->getMaxPrefixLength()) {
                break;
            }

            $matched->setPrefixLength($newPrefixLength);
            $unmatched->setPrefixLength($newPrefixLength);
            $unmatched->setIP($matched->getLastIP()->next());
        }

        sort($networks);

        return $networks;
    }

    public static function parse($data)
    {
        if (preg_match('~^(.+?)/(\d+)$~', $data, $matches)) {
            $ip = IP::parse($matches[1]);
            $netmask = self::prefix2netmask((int)$matches[2], $ip->getVersion());
        } elseif (strpos($data, ' ')) {
            list($ip, $netmask) = explode(' ', $data, 2);
            $ip = IP::parse($ip);
            $netmask = IP::parse($netmask);
        } else {
            $ip = IP::parse($data);
            $netmask = self::prefix2netmask($ip->getMaxPrefixLength(), $ip->getVersion());
        }

        return new self($ip, $netmask);
    }

    /**
     * @param int $prefixLength
     * @param string $version
     * @return IP
     * @throws \Exception
     */
    public static function prefix2netmask($prefixLength, $version)
    {
        if (!in_array($version, array(IPv4::version, IPv6::version))) {
            throw new \Exception("Wrong IP version");
        }

        $maxPrefixLength = $version === IPv4::version
            ? IPv4::length
            : IP::v6Length;

        if (!is_numeric($prefixLength)
            || !($prefixLength >= 0 && $prefixLength <= $maxPrefixLength)
        ) {
            throw new \Exception('Invalid prefix length');
        }

        $binIP = str_pad(str_pad('', (int)$prefixLength, '1'), $maxPrefixLength, '0');

        return IP::parseBin($binIP);
    }

    /**
     * @return IP
     */
    public function getFirstIP()
    {
        return $this->getNetwork();
    }

    /**
     * @return IP
     * @throws \Exception
     */
    public function getLastIP()
    {
        return $this->getBroadcast();
    }

    public function setPrefixLength($prefixLength)
    {
        $this->setNetmask(self::prefix2netmask((int)$prefixLength, $this->ip->getVersion()));
    }

    /**
     * @param int $prefixLength
     * @return Network[]
     * @throws \Exception
     */
    public function moveTo($prefixLength)
    {
        $maxPrefixLength = $this->ip->getMaxPrefixLength();

        if ($prefixLength <= $this->getPrefixLength() || $prefixLength > $maxPrefixLength) {
            throw new \Exception('Invalid prefix length ');
        }

        $netmask = self::prefix2netmask($prefixLength, $this->ip->getVersion());
        $networks = array();

        $subnet = clone $this;
        $subnet->setPrefixLength($prefixLength);

        while ($subnet->ip->inAddr() <= $this->getLastIP()->inAddr()) {
            $networks[] = $subnet;
            $subnet = new self($subnet->getLastIP()->next(), $netmask);
        }

        return $networks;
    }

    /**
     * @return IP|mixed
     * @throws \Exception
     */
    public function current()
    {
        return $this->getFirstIP()->next($this->position);
    }

    /**
     * @return int|mixed
     */
    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function valid()
    {
        return strcmp($this->getFirstIP()->next($this->position)->inAddr(), $this->getLastIP()->inAddr()) <= 0;
    }

    /**
     * @return int
     */
    public function count()
    {
        return (integer)$this->getBlockSize();
    }

}
