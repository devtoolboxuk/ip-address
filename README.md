# IP Address


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