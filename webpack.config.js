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

    .addEntry('simsy_cms_back', './src/assets/js/back/simsy.ts')
    .addEntry('simsy_cms_front', './src/assets/js/front/simsy.ts')

    .copyFiles(
        {
            from: "./src/assets/images",

            // optional target path, relative to the output dir
            to: "images/[path][name].[ext]",

            // if versioning is enabled, add the file hash too
            //to: 'images/[path][name].[hash:8].[ext]',
            // only copy files matching this pattern
            pattern: /\.(png|jpg|jpeg|mp4|svg|mov|mp4|pdf|webp)$/,
        }
    )

    .enableSourceMaps(false)
    .enableVersioning(false)
    .disableSingleRuntimeChunk()
    .enableSassLoader()
    .enableTypeScriptLoader()
    .enableForkedTypeScriptTypesChecking()
    .cleanupOutputBeforeBuild()

module.exports = Encore.getWebpackConfig();