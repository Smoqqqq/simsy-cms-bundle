# Simsy CMS Bundle
A simple & easy CMS Bundle for Symfony

## Installation
Install the bundle with `composer require smoq/simsy-cms-bundle`

Create a migration with `php bin/console make:migration` and run it with `php bin/console doctrine:migrations:migrate`
You will see a bunch of tables created in your database with the prefix `simsy_`.

### Configuring routes
**TODO: include this configuration in a recipe**  
You need to register Simsy's routes in your application. To do this, import our routes.yaml configuration file :
    
```yaml
# config/routes.yaml
simsy:
    resource: '@SimsyCMS/Resources/config/routes.yaml'
```

## Translations
Simsy CMS is translated in English and French. I will be more than pleased to have more languages. If you want to contribute, please fell free to open a PR or issue.