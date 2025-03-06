<?php

namespace Nxtech\CustomS3Media\Helper;

use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
    const S3_URL = 'https://d2rdfqk6u5busa.cloudfront.net/amazon_main_images/';
    public function getAwsImagesJson($imagesArr)
    {
        $imagesItems = [];

        foreach ($imagesArr as $images) {
            $imageUrl = $images['url'];
            $main = $images['main'];

            if ($imageUrl == '') {
                continue;
            }
            $imagesItems[] = [
                'thumb' => $imageUrl,
                'img' => $imageUrl,
                'full' => $imageUrl,
                'caption' => '',
                'position' => '0',
                'isMain' => $main,
                'type' => 'image',
                'videoUrl' => null,
            ];
        }
        return $imagesItems;
    }
    public function getCustomImageArray($s3Urls)
    {
        $s3Urls = explode(",", $s3Urls); // This returns the raw textarea value
        $s3images = [];
        $i = 1;
        foreach ($s3Urls as $key => $urls) {
            $main = false;
            if ($i == 1) {
                $main = true;
            }
            $s3images[] = ["url" => self::S3_URL . $urls, "position" => $i, "main" => $main];
            $i++;
        }

        return $this->getAwsImagesJson($s3images);
    }
}