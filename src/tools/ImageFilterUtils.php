<?php

namespace cotcot\tools;

/**
 * Image filter utils.
 * @author Ludovic Martin <contact@ludovicmartin.fr>
 */
class ImageFilterUtils {

    /**
     * Filter an image.
     * @param string $imageMagickPath image magick path
     * @param array $cachePath cache path for filtered images
     * @param string $filename image file name
     * @param ImageFilterParams $params filter params
     * @return string|null output filename or null on error
     */
    public static function filterImage($imageMagickPath, $cachePath, $filename, $params) {
        if (
                $params->maxWidth > 0 && $params->maxWidth < 4096 &&
                $params->maxHeight > 0 && $params->maxHeight < 4096 &&
                $params->quality >= 0 && $params->quality <= 100 &&
                ($params->rotation === null || ($params->rotation !== null && $params->rotation > 0 && $params->rotation % 90 == 0)) &&
                ($params->cropX === null || ($params->cropX !== null && $params->cropX >= 0)) &&
                ($params->cropY === null || ($params->cropY !== null && $params->cropY >= 0)) &&
                ($params->cropWidth === null || ($params->cropWidth !== null && $params->cropWidth > 0)) &&
                ($params->cropHeight === null || ($params->cropHeight !== null && $params->cropHeight > 0))
        ) {
            $cacheFilename = self::buildCacheFilename($cachePath, $filename, $params);
            $sourceFileTimestamp = @filemtime($filename);
            if ($sourceFileTimestamp !== false) {
                $cacheFileTimestamp = @filemtime($cacheFilename);
                if ($cacheFileTimestamp === false || $sourceFileTimestamp >= $cacheFileTimestamp) {
                    $commandLine = array();
                    $commandLine[] = $imageMagickPath;
                    $commandLine[] = '-quiet';
                    $commandLine[] = escapeshellarg($filename);
                    //Orientation automatique en fonction des indication des champs EXIF
                    $commandLine[] = '-auto-orient';
                    //Rotation
                    if ($params->rotation != null) {
                        $commandLine[] = '-rotate';
                        $commandLine[] = intval($params->rotation);
                    }
                    //Recadrage
                    if ($params->cropX != null && $params->cropY != null && $params->cropWidth != null && $params->cropHeight != null) {
                        $commandLine[] = '-crop';
                        $commandLine[] = intval($params->cropWidth) . 'x' . intval($params->cropHeight) . '+' . intval($params->cropX) . '+' . intval($params->cropY);
                    }
                    //Redimentionner l'image 
                    if ($params->maxWidth != null && $params->maxHeight != null) {
                        $commandLine[] = '-resize';
                        $commandLine[] = intval($params->maxWidth) . 'x' . intval($params->maxHeight);
                    }
                    $commandLine[] = '-filter';
                    $commandLine[] = 'Lanczos';
                    $commandLine[] = '-quality';
                    $commandLine[] = intval($params->quality);
                    $commandLine[] = '-define';
                    $commandLine[] = 'jpeg:optimize-coding=true';
                    $commandLine[] = '-format';
                    $commandLine[] = 'JPG';
                    $commandLine[] = escapeshellarg($cacheFilename);
                    $terminalOutput = null;
                    $returnValue = null;
                    exec(implode(' ', $commandLine), $terminalOutput, $returnValue);
                    if ($returnValue !== 0) {
                        return null;
                    }
                }
                return $cacheFilename;
            }
        }
        return null;
    }

    /**
     * Retourne la date du fichier de cache correspondant à une image devant subir un certain type de retraitement.
     * @param array $cachePath cache path for filtered images
     * @param string $filename image file name
     * @param ImageFilterParams $params filter params
     * @return int|null timestamp or null if no cache file
     */
    public static function getCacheFileDate($cachePath, $filename, $params) {
        $timestamp = @filemtime(self::buildCacheFilename($cachePath, $filename, $params));
        return $timestamp !== false ? $timestamp : null;
    }

    /**
     * Build a cache filename.
     * @param array $cachePath cache path for filtered images
     * @param string $filename image file name
     * @param ImageFilterParams $params filter params
     * @return string filename
     */
    public static function buildCacheFilename($cachePath, $filename, $params) {
        $temp = array();
        $temp[] = 'resample';
        if ($params->rotation != null) {
            $temp[] = 'R' . $params->rotation;
        }
        if ($params->cropX != null && $params->cropY != null && $params->cropWidth != null && $params->cropHeight != null) {
            $temp[] = 'CX' . $params->cropX;
            $temp[] = 'CY' . $params->cropY;
            $temp[] = 'CW' . $params->cropWidth;
            $temp[] = 'CH' . $params->cropHeight;
        }
        $temp[] = 'MW' . $params->maxWidth;
        $temp[] = 'MH' . $params->maxHeight;
        $temp[] = 'Q' . $params->quality;
        $temp[] = $filename;
        return $cachePath . '/' . preg_replace('/[^a-zA-Z0-9]/', '_', implode(' ', $temp));
    }

}
