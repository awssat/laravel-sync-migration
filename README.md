# Laravel Sync Migration

<p align="center"> 
    <img src="https://i.imgur.com/OP83jHA.jpg" alt="Laravel Sync Migration">
</p>


[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]


## Introduction
It's a tool to help ease the repetitive migration process during developing a Laravel project without migrate:fresh your database every time you change you schemas.


## Table of Contents
  * [Features](#features)
  * [Install](#install)
  * [Usage](#usage)
  * [Contributing](#contributing)
  * [Credits](#credits)
  * [License](#license)
  
  
  
## Features
- Easy to install, no need to add or change your schemas or project files.
- Sync schema changes to database instantly. No need to repeat fresh or seed commands everytime.
- Interactive. It will give you choices to not force unwanted changes to your database.




## Install

Via Composer
```bash
composer require awssat/laravel-sync-migration
```


#### Before Laravel 5.5
In Laravel 5.4. you'll manually need to register the `Awssat\SyncMigration::class` service provider in `config/app.php`.


## Usage

It's simple. Anytime you want sync schema files to database run this command:
```
php artisan migrate:sync
```


<p align="center"> 
    <img src="https://thumbs.gfycat.com/SplendidFlatAustrianpinscher-size_restricted.gif" alt="Laravel Sync Migration">
</p>


## Contributing

You are very welcome to contribute and improve this package.


## Credits

- [Bader][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/awssat/laravel-sync-migration.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://travis-ci.org/awssat/laravel-sync-migration.svg?branch=master
[ico-code-quality]: https://scrutinizer-ci.com/g/awssat/laravel-sync-migration/badges/quality-score.png?b=master
[ico-downloads]: https://img.shields.io/packagist/dt/awssat/laravel-sync-migration.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/awssat/laravel-sync-migration
[link-travis]: https://travis-ci.org/awssat/laravel-sync-migration
[link-scrutinizer]: https://scrutinizer-ci.com/g/awssat/laravel-sync-migration/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/awssat/laravel-sync-migration
[link-downloads]: https://packagist.org/packages/awssat/laravel-sync-migration
[link-author]: https://github.com/if4lcon
[link-contributors]: ../../contributors
