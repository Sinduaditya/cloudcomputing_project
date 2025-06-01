<?php
// filepath: f:\UGM\cloudcomputing\cloudcomputing_project\app\Services\CloudinaryService.php

namespace App\Services;

use Cloudinary\Cloudinary;
use Cloudinary\Configuration\Configuration;
use Exception;
use Illuminate\Support\Facades\Log;

class CloudinaryService
{
    protected $cloudinary;

    public function __construct()
    {
        Configuration::instance([
            'cloud' => [
                'cloud_name' => config('cloudinary.cloud_name'),
                'api_key' => config('cloudinary.api_key'),
                'api_secret' => config('cloudinary.api_secret'),
            ],
            'url' => [
                'secure' => true,
            ],
        ]);

        $this->cloudinary = new Cloudinary();
    }

    /**
     * Upload file to Cloudinary
     */
    public function uploadFile($filePath, array $options = [])
    {
        try {
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $resourceType = $this->getResourceType($extension);

            $defaultOptions = [
                'resource_type' => $resourceType,
                'folder' => config('cloudinary.uploads.folder', 'downloads'),
                'use_filename' => true,
                'unique_filename' => true,
                'overwrite' => false,
            ];

            if ($resourceType === 'video') {
                $defaultOptions = array_merge($defaultOptions, [
                    'quality' => 'auto',
                    'format' => 'mp4',
                    'video_codec' => 'h264',
                ]);
            }

            $uploadOptions = array_merge($defaultOptions, $options);

            Log::info('Uploading to Cloudinary', [
                'file_path' => $filePath,
                'options' => $uploadOptions
            ]);

            $result = $this->cloudinary->uploadApi()->upload($filePath, $uploadOptions);

            return [
                'success' => true,
                'public_id' => $result['public_id'],
                'secure_url' => $result['secure_url'],
                'url' => $result['url'],
                'bytes' => $result['bytes'],
                'format' => $result['format'],
                'resource_type' => $result['resource_type'],
            ];

        } catch (Exception $e) {
            Log::error('Cloudinary upload failed', [
                'error' => $e->getMessage(),
                'file_path' => $filePath
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete file from Cloudinary
     */
    public function deleteFile($publicId, $resourceType = 'video')
    {
        try {
            $result = $this->cloudinary->uploadApi()->destroy($publicId, [
                'resource_type' => $resourceType
            ]);

            return [
                'success' => true,
                'result' => $result['result']
            ];

        } catch (Exception $e) {
            Log::error('Cloudinary delete failed', [
                'error' => $e->getMessage(),
                'public_id' => $publicId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Get signed URL with expiry
     */
    public function getSignedUrl($publicId, $resourceType = 'video', $expiresIn = 3600)
    {
        try {
            $options = [
                'resource_type' => $resourceType,
                'expires_at' => time() + $expiresIn,
                'type' => 'authenticated'
            ];

            return $this->cloudinary->image($publicId)
                ->addTransformation($options)
                ->toUrl();

        } catch (Exception $e) {
            Log::error('Failed to generate signed URL', [
                'error' => $e->getMessage(),
                'public_id' => $publicId
            ]);
            return null;
        }
    }

    /**
     * Get video thumbnail
     */
    public function getVideoThumbnail($publicId)
    {
        try {
            return $this->cloudinary->video($publicId)
                ->resize(\Cloudinary\Transformation\Resize::thumbnail()->width(300)->height(200))
                ->format('jpg')
                ->toUrl();

        } catch (Exception $e) {
            Log::error('Failed to generate thumbnail', [
                'error' => $e->getMessage(),
                'public_id' => $publicId
            ]);
            return null;
        }
    }

    /**
     * Determine resource type based on file extension
     */
    protected function getResourceType($extension)
    {
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'flv', 'webm', 'mkv', '3gp'];
        $audioExtensions = ['mp3', 'wav', 'flac', 'aac', 'm4a', 'ogg'];
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp'];

        if (in_array($extension, $videoExtensions) || in_array($extension, $audioExtensions)) {
            return 'video'; // Cloudinary treats audio as video resource type
        } elseif (in_array($extension, $imageExtensions)) {
            return 'image';
        } else {
            return 'raw';
        }
    }
}
