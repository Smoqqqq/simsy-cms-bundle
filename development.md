# While in development

Dump JS traductions (I here assume that you have installed the bundle in a local symfony project)
```bash
php bin/console bazinga:js-translation:dump vendor/smoq/simsy-cms-bundle/src/Resources/public
php bin/console assets:install --symlink
```