camael24/sohapi
=====

Sohapi est un générateur de documentation API statique de classe PHP se basant sur la reflection et complete ses informations avec des annotations.
Nous déclarons à sohapi des classes PHP et non des fichiers, il faut donc procéder a manuellement à l'autoloading

Pré-requis
-----
Votre classe (ou librairie) doit être correctement autoloader via composer ou via votre propre autoloader

Quick Usage
-----

```PHP
$api = new \Sohapi\Export();
$api
    ->classname('/Foo/Bar')
    ->all()
    ->internal()
    ->resolution('#^/#', '(?<classname>[^\\.]).html')
    ->proxy(new \Sohapi\Proxy\Classes())
    ->export(new \Sohapi\Formatter\Html(__DIR__ . '/html'));

```

Demo
-----

See an demo (here)[http://camael24.github.io/sohapi/]