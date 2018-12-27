# IP Address

[![Build Status](https://api.travis-ci.org/devtoolboxuk/ip-address.svg?branch=master)](https://travis-ci.org/devtoolboxuk/ip-address)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/devtoolboxuk/ip-address/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/devtoolboxuk/ip-address/?branch=master)
[![Coveralls](https://coveralls.io/repos/github/devtoolboxuk/ip-address/badge.svg?branch=master)](https://coveralls.io/github/devtoolboxuk/ip-address?branch=master)
[![CodeCov](https://codecov.io/gh/devtoolboxuk/ip-address/branch/master/graph/badge.svg)](https://codecov.io/gh/devtoolboxuk/ip-address)

[![Latest Stable Version](https://img.shields.io/packagist/v/devtoolboxuk/ip-address.svg?style=flat-square)](https://packagist.org/packages/devtoolboxuk/ip-address)
[![Total Downloads](https://img.shields.io/packagist/dt/devtoolboxuk/ip-address.svg?style=flat-square)](https://packagist.org/packages/devtoolboxuk/ip-address)
[![License](https://img.shields.io/packagist/l/devtoolboxuk/ip-address.svg?style=flat-square)](https://packagist.org/packages/devtoolboxuk/ip-address)

## Table of Contents

- [Background](#Background)
- [Usage](#Usage)
- [Maintainers](#Maintainers)
- [License](#License)

## Background
Used to convert an IP address to an Integer, or for a IPv6 IP address, a long
it will also convery an IP range into an array of integers representing the IP address.

Initially created so that I could store IP addresses in a database and do a look up against it.

## Usage

```sh
$ composer require devtoolboxuk/ip-address
```

Then include Composer's generated vendor/autoload.php to enable autoloading:

```sh
require 'vendor/autoload.php';
```

```sh
use devtoolboxuk/ip-address;

IP::parse($ipAddress)->long
IP::parse($ipAddress)->toLong()

```


## Maintainers

[@DevToolboxUk](https://github.com/DevToolBoxUk).


## License

[MIT](LICENSE) © DevToolboxUK