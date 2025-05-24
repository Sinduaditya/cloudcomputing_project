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

    /**
     * Create a new service instance.
     */
    public function __construct()
    {
        // Configure Cloudinary from environment variables or config
        // Expects CLOUDINARY_URL in your .env file
        $this->cloudinary = new Cloudinary();
    }

    /**
     * Upload a file to Cloudinary
     */
    public function uploadFile($filePath, array $options = [])
    {
        try {
            Log::info('Uploading file to Cloudinary', [
                'file' => $filePath,
                'options' => $options
            ]);

            if (!file_exists($filePath)) {
                throw new Exception("File not found: {$filePath}");
            }

            // Default options
            $defaultOptions = [
                'resource_type' => 'auto',
                'unique_filename' => true,
                'overwrite' => true
            ];

            // Merge with custom options
            $uploadOptions = array_merge($defaultOptions, $options);

            // Upload to Cloudinary
            $result = $this->cloudinary->uploadApi()->upload($filePath, $uploadOptions);

            Log::info('Cloudinary upload successful', [
                'public_id' => $result['public_id'],
                'url' => $result['secure_url']
            ]);

            return $result;
        } catch (Exception $e) {
            Log::error('Cloudinary upload failed', [
                'error' => $e->getMessage(),
                'file' => $filePath
            ]);
            throw $e;
        }
    }

    /**
     * Delete a file from Cloudinary
     */
    public function deleteFile($publicId, $resourceType = 'video')
    {
        try {
            Log::info('Deleting file from Cloudinary', [
                'public_id' => $publicId,
                'resource_type' => $resourceType
            ]);

            $result = $this->cloudinary->uploadApi()->destroy($publicId, [
                'resource_type' => $resourceType
            ]);

            Log::info('Cloudinary delete successful', [
                'result' => $result
            ]);

            return $result;
        } catch (Exception $e) {
            Log::error('Cloudinary delete failed', [
                'error' => $e->getMessage(),
                'public_id' => $publicId
            ]);
            throw $e;
        }
    }
}
