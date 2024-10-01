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
    resource: '@SimsyCMSBundle/Resources/config/routes.yaml'
```

### Including assets
To include Simsy's assets in your application, you need to include the following in your base template :

```twig
{% block stylesheets %}
    <link rel="stylesheet" href="{{ asset('bundles/simsy-cms/simsy.css') }}">
{% endblock %}

{% block javascripts %}
    <script src="{{ asset('bundles/simsy-cms/simsy.js') }}"></script>
{% endblock %}
```