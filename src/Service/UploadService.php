<?php

namespace App\Service;

use Aws\S3\S3Client;
use Aws\S3\Exception\S3Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class UploadService
{
    /**
     * @var string $region
     */
    private $region;

    /**
     * @var string $key
     */
    private $key;

    /**
     * @var string $secret
     */
    private $secret;

    /**
     * @var string $bucket
     */
    private $bucket;

    /**
     * UploadService constructor.
     *
     * @param $region
     * @param $key
     * @param $secret
     * @param $bucket
     */
    public function __construct($region, $key, $secret, $bucket)
    {
        $this->region = $region;
        $this->key = $key;
        $this->bucket = $bucket;
        $this->secret = $secret;
    }

    /**
     * @param UploadedFile $file
     *
     * @return mixed|null
     *
     * @throws \Exception
     */
    public function uploadFile($file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $newFilename = uniqid().$originalFilename.'.'.$file->guessExtension();

        $s3 = new S3Client([
            'region'  => $this->region,
            'version' => '2006-03-01',
            'signature_version' => 'v4',
            'validate' => false,
            'credentials' => [
                'key'    => $this->key,
                'secret' => $this->secret,
            ],
            'options' => [
                'scheme' => 'http',
            ]
        ]);

        try {
            $result = $s3->putObject([
                'Bucket'        => $this->bucket,
                'Key'           => $newFilename,
                'SourceFile'    => $file->getRealPath(),
                'ACL'           => 'public-read-write',
            ]);

            return $result->get('ObjectURL');
        } catch (S3Exception  $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
