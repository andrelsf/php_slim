# Slim Framework

## Install composer

```bash
$ curl -sS https://getcomposer.org/installer -o composer-setup.php
$ HASH=a5c698ffe4b8e849a443b120cd5ba38043260d5c4023dbf93e1558871f1f07f58274fc6f4c93bcfd858c6bd0775cd8d1
$ php -r "if (hash_file('SHA384', 'composer-setup.php') === '$HASH') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
$ sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
$ php -r "unlink('composer-setup.php');"
```

## Install Slim framework

```bash
$ composer require slim/slim:4.0.0
$ composer require slim/psr7
$ composer require nyholm/psr7 nyholm/psr7-server
$ composer require guzzlehttp/psr7 http-interop/http-factory-guzzle
$ composer require zendframework/zend-diactoros
```


