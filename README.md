# SmartBench CLI tool
[![Build Status](https://travis-ci.com/pawel-brzezinski/smart-bench-cli.svg?branch=master)](https://travis-ci.com/pawel-brzezinski/smart-bench-cli) [![codecov](https://codecov.io/gh/pawel-brzezinski/smart-bench-cli/branch/master/graph/badge.svg)](https://codecov.io/gh/pawel-brzezinski/smart-bench-cli)

PHP CLI application for PHP libraries benchmark tests with use [PHPBench](https://phpbench.readthedocs.io).

Check simple website with benchmark results: [https://bench.smartint.pl](https://bench.smartint.pl)

**List of benchmarks**
* [Cache libraries](#cache-libraries)
* [Serializer libraries](#serializer-libraries)

## Cache libraries
The basic benchmark of cache libraries with division into several groups.

#### Assumptions
1. Each benchmark is preceded by injection of **100** elements of cache storage.
2. Each value saved and read from the cache is an example [Book](src/Model/Book.php) object.
3. Each task in the benchmark is performed by **multiple** iteration with **multiple** revs.

#### Tested libraries
1. **[PHP Cache](https://www.php-cache.com/)** [write, write with tags, read, invalidate tags]
2. **[PhpFastCache](https://www.phpfastcache.com/)** [write, write with tags, read, invalidate tags]
3. **[Scrapbook](https://www.scrapbook.cash/)** [write, read]
4. **[Stash](https://www.stashphp.com/)** [write, read]
5. **[Symfony Cache Component](https://github.com/symfony/cache)** [write, write with tags, read, invalidate tags]

#### Used adapters
1. Filesystem [PHP Cache, PhpFastCache, Scrapbook, Stash, Symfony Cache Component]
2. Memcached [PHP Cache, PhpFastCache, Scrapbook, Stash, Symfony Cache Component]
3. Redis (PhpRedis) [PHP Cache, PhpFastCache, Scrapbook, Stash, Symfony Cache Component]
4. Redis (Predis) [PHP Cache, PhpFastCache, Symfony Cache Component]

#### Attention
Each of the tested libraries has a lot of own functionalities that have not been tested but may be crucial for choose them to your project.

## Serializer libraries
The basic benchmark of serializer libraries with division into several groups.

#### Assumptions
1. Each value serialized and deserialized is an example [Book](src/Model/Book.php) object.
2. Each task in the benchmark is performed by **multiple** iteration with **multiple** revs.

#### Tested libraries
1. **[Better Serializer](https://github.com/better-serializer/better-serializer)** [serialize, deserialize]
2. **[JMS Serializer](https://jmsyst.com/libs/serializer)** [serialize, deserialize]
3. **[Serializard](https://github.com/thunderer/Serializard)** [serialize, deserialize]
4. **[Symfony Serializer](https://symfony.com/doc/current/components/serializer.html)** [serialize, deserialize]

#### Used serializer formats
1. JSON [Better Serializer, JMS Serializer, Serializard, Symfony Serializer]
2. XML [JMS Serializer, Serializard, Symfony Serializer]

#### Attention
Each of the tested libraries has a lot of own functionalities that have not been tested but may be crucial for choose them to your project.
