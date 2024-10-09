# Simsy CMS Bundle
A simple & easy CMS Bundle for Symfony

## Installation
Install the bundle with `composer require smoq/simsy-cms-bundle`

Create a migration with `php bin/console make:migration` and run it with `php bin/console doctrine:migrations:migrate`
You will see a bunch of tables created in your database with the prefix `simsy_`.

### Configuration
See [configuration reference](src/docs/configuration.md).

## Translations
Simsy CMS is translated in English and French. I will be more than pleased to have more languages. If you want to contribute, please fell free to open a PR or issue.

## Dependencies
Video compression relies on the [php-ffmpeg](https://github.com/PHP-FFMpeg/PHP-FFMpeg) library. You need to have [ffmpeg](https://ffmpeg.org/download.html) installed on your server, and available in its `$PATH`.