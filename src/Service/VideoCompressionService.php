<?php

namespace Smoq\SimsyCMS\Service;

use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\Ogg;
use FFMpeg\Format\Video\WebM;
use FFMpeg\Format\Video\WMV;
use FFMpeg\Format\Video\WMV3;
use FFMpeg\Format\Video\X264;
use FFMpeg\Format\VideoInterface;
use Smoq\SimsyCMS\DependencyInjection\ConfigurationException;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\File\File;

class VideoCompressionService
{
    public function __construct(private readonly ParameterBagInterface $parameterBag)
    {
    }

    /**
     * @throws ConfigurationException if the video codec / audio codec combination is not supported
     */
    private function getVideoFormat(string $audioCodec, string $videoCodec): VideoInterface
    {
        $availableFormats = [
            new Ogg(),
            new WebM(),
            new WMV(),
            new WMV3(),
            new X264(),
        ];

        /**
         * @var VideoInterface $format
         */
        foreach ($availableFormats as $format) {
            if (in_array($videoCodec, $format->getAvailableVideoCodecs()) && in_array($audioCodec, $format->getAvailableAudioCodecs())) {
                $class = get_class($format);
                return new $class($audioCodec, $videoCodec);
            }
        }

        throw new ConfigurationException("No video format found for video codec $videoCodec and audio codec $audioCodec");
    }

    public function compress(File $file): File
    {
        try {
            $config = $this->parameterBag->get('simsy_cms.video_compression') ?? [];
        } catch (ParameterNotFoundException) {
            $config = [];
        }

        $ffmpegConfigTemp = $config['ffmpeg_config'] ?? [];
        $ffmpegConfig = [];

        foreach ($ffmpegConfigTemp as $category => $data) {
            foreach ($data as $key => $value) {
                $ffmpegConfig[$category.'.'.$key] = $value;
            }
        }

        $ffmpeg = FFMpeg::create($ffmpegConfig);

        $video = $ffmpeg->open($file->getPathname());

        // Define the video format and compression settings
        $format = $this->getVideoFormat($config['audio_codec'], $config['video_codec']);

        // Adjust the bitrate (lower values will reduce the file size)
        $format->setKiloBitrate(
            $config['video_kilo_bitrate']
        ); // 500 kbps for video bitrate

        // Set audio bitrate and channels (optional)
        $format->setAudioKiloBitrate(
            $config['audio_kilo_bitrate']
        ); // 128 kbps for audio bitrate
        $format->setAudioChannels(
            $config['audio_channels']
        );

        if ($config['video_extension'] !== false) {
            $extension = $config['video_extension'];
        } else {
            $extension = $file->guessExtension();
        }

        $newPath = preg_replace("/\.[0-9a-z]+$/", '--compressed.' . $extension, $file->getPathname());

        // Save the compressed video to the output path
        $video->save($format, $newPath);

        return new File($newPath);
    }
}