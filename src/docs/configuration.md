# Configuration

## Configuring routes
This is a necessary step to make Simsy CMS work : you need to register Simsy's routes in your application. To do this, you can either include every route or define prefixes for admin and public routes, by importing them separately.
```yaml
# config/routes.yaml

# import every route
simsy_cms:
    resource: '@SimsyCMSBundle/Resources/config/routes.yaml'

# or define prefixes for admin and public routes
simsy_cms_admin:
    resource: '@SimsyCMSBundle/Resources/config/admin_routes.yaml'
    prefix: '/simsy'

simsy_cms_public:
    resource: '@SimsyCMSBundle/Resources/config/public_routes.yaml'
    prefix: '/blog'
```