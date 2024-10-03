const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('./src/Resources/public/')
    .setPublicPath('/bundles/simsycms/')
    .setManifestKeyPrefix('bundles/simsycms')

    .addEntry('simsy_cms', './src/assets/js/simsy.ts')

    .enableSourceMaps(false)
    .enableVersioning(false)
    .disableSingleRuntimeChunk()
    .enableSassLoader()
    .enableTypeScriptLoader()
    .enableForkedTypeScriptTypesChecking()
    .cleanupOutputBeforeBuild()

module.exports = Encore.getWebpackConfig();