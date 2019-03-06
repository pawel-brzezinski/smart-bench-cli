# SmartBench CLI tool
PHP CLI application for PHP libraries benchmark tests with use [PHPBench](https://phpbench.readthedocs.io).

**List of benchmarks**
* [Cache libraries](#cache-libraries)

## Cache libraries
The basic benchmark of cache libraries with division into several groups.

#### Features
1. Each benchmark is preceded by injection of **1000** elements of cache storage.
2. Each value saved and read from the cache is an example [Book](src/Model/Book.php) object.
3. Each task in the benchmark is performed by **5** iteration and **1000** revs by iteration.

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
