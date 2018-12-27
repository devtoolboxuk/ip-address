# IP Address

[![Build Status](https://api.travis-ci.org/devtoolboxuk/ipAddress.svg?branch=master)](https://travis-ci.org/devtoolboxuk/ipAddress)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/devtoolboxuk/ipAddress/master.svg?style=flat-square)](https://scrutinizer-ci.com/g/devtoolboxuk/ipAddress/?branch=master)
[![Coveralls](https://coveralls.io/repos/github/devtoolboxuk/ipAddress/badge.svg?branch=master)](https://coveralls.io/github/devtoolboxuk/ipAddress?branch=master)
[![CodeCov](https://codecov.io/gh/devtoolboxuk/ipAddress/branch/master/graph/badge.svg)](https://codecov.io/gh/devtoolboxuk/ipAddress)

[![Latest Stable Version](https://img.shields.io/packagist/v/devtoolboxuk/ipAddress.svg?style=flat-square)](https://packagist.org/packages/devtoolboxuk/ipAddress)
[![Total Downloads](https://img.shields.io/packagist/dt/devtoolboxuk/ipAddress.svg?style=flat-square)](https://packagist.org/packages/devtoolboxuk/ipAddress)
[![License](https://img.shields.io/packagist/l/devtoolboxuk/ipAddress.svg?style=flat-square)](https://packagist.org/packages/devtoolboxuk/ipAddress)

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
$ composer require devtoolboxuk/ipAddress
```

Then include Composer's generated vendor/autoload.php to enable autoloading:

```sh
require 'vendor/autoload.php';
```

```sh
use devtoolboxuk/ipAddress;

IP::parse($ipAddress)->long
IP::parse($ipAddress)->toLong()

```


## Maintainers

[@DevToolboxUk](https://github.com/DevToolBoxUk).


## License

[MIT](LICENSE) © DevToolboxUK