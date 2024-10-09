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

## Configuring the bundle
You can configure the bundle by creating a `simsy_cms.yaml` file in your `config/packages` directory. Here is an example of a configuration file with all available options :
```yaml
# config/packages/simsy_cms.yaml

simsy_cms:
    video_compression:
        enabled: true                                                   # Enable or disable video compression (enabled by default)
        ffmpeg_config:
            ffmpeg:
                binaries: 'C:\ProgramData\chocolatey\bin\ffmpeg.exe'    # Local ffmpeg binary path - only needed if not in $PATH
            ffprobe:
                binaries: 'C:\ProgramData\chocolatey\bin\ffprobe.exe'   # Local ffprobe binary path - only needed if not in $PATH

        # These are default values
        audio_codec: 'aac'                                              # Any audio codec supported by ffmpeg
        video_codec: 'libx264'                                          # Any video codec supported by ffmpeg - has to work with the video_extension
        video_kilo_bitrate: 500
        audio_kilo_bitrate: 128
        audio_channels: 2                                               # Number of audio channels
        video_extension: 'mp4'                                          # Format in which to save videos, false to preserve original extension
        
    image_compression:
        enabled: true                                                   # Enable or disable image compression (enabled by default)
        quality: 80                                                     # Image quality (0-100, 100 being the best quality)

    custom_blocks:
        my_custom_block:
            class: 'App\Entity\CustomBlock'                             # The class of your custom block
            name: 'Custom Block'                                        # The name of your custom block. can be a translation key
            description: 'Custom block description'                     # [optional] The description of your custom block. can be a translation key
            template_path: 'block_template/custom_block.html.twig'      # The path to the template of your custom block
            form_class: 'App\Form\CustomBlockType'                      # [optional] The form class of your custom block. If ommited, App\Form\{BlockName}Type will be assumed
            img_src: 'build/images/custom_block.png'                    # [optional] The path to the image of your custom block (as in used with the asset twig function)
```