<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Validation\Factory as Validator;
use Illuminate\Http\UploadedFile as File;
use Illuminate\Validation\ValidationException;

class FileManagementService
{
    private $storage;
    private $validator;

    /**
     * @param Storage $storage
     * @param Validator $validator
     * 
     */
    public function __construct(Storage $storage, Validator $validator)
    {
        $this->storage = $storage;
        $this->validator = $validator;
    }

    /**
     * Get the storage path for the uploaded file.
     *
     * @param  array $fileData
     * @return string
     * @return exception
     */
    public function upload(File $file, array $fileData)
    {
        $validation = $this->validator->make(
            $fileData,
            [
                'path' => 'required',
                'name' => 'required',
            ]
        );

        if ($validation->fails()) {
            throw new ValidationException($validation);
        }
        $storagePath = $this->storage->putFileAs($fileData['path'], $file, $fileData['name']);

        return $storagePath;
    }

}
