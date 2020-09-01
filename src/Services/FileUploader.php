<?php


namespace Services;

use finfo;

class FileUploader
{
    /** @var string */
    private $uploadDir;

    /** @var string[] */
    private $mimeTypeAllowed = self::MIME_TYPE_IMAGE;

    /** @var int */
    private $maxSize = 512000;

    /** @var array */
    private $file;

    /** @var array */
    private $errors = array();

    const MIME_TYPE_IMAGE = ['image/jpeg', 'image/gif', 'image/png'];
    const MIME_TYPE_PDF = ['application/pdf'];


    /**
     * FileUploader constructor.
     * @param string $uploadDir path relative to '/public' where the file must be uploaded
     * @param string $inputName the name of the input field in the form
     * @param array $mimeType MIME type allowed
     * @param int $maxSize max file size
     */
    public function __construct(string $uploadDir, string $inputName, array $mimeType, $maxSize = 10248576)
    {
        $this->uploadDir = rtrim($uploadDir, '/');
        $this->file['name'] = basename($_FILES[$inputName]['name']);
        $this->file['error']= (int)$_FILES[$inputName]['error'];
        $this->file['tmp_name'] = $_FILES[$inputName]['tmp_name'];
        $this->setMaxSize($maxSize);
        $this->setMimeTypeAllowed($mimeType);
    }


    /**
     * Put the uploaded file to the uploads if everything is ok
     * @return false|string
     */
    public function upload(): bool
    {
        if ($this->file['error'] == UPLOAD_ERR_OK) {
            return $this->validateFile() && $this->moveToUploadDir();
        }
        $this->errors[] = "Erreur lors de la réception du fichier";
        return false;
    }


    /**
     * Validate the file if the MIME type and size is correct
     * @return bool
     */
    private function validateFile(): bool
    {
        return $this->validateMimeType() && $this->validateFileSize();
    }


    /**
     * Move the uploaded file to the uploads directory
     * @return bool
     */
    private function moveToUploadDir(): bool
    {
        if (!move_uploaded_file($this->file['tmp_name'], ROOT_DIR . '/public' . $this->uploadDir . '/' . $this->file['name'])) {
            $this->errors[] = "Erreur lors du traitement du fichier";
            return false;
        }
        return true;
    }


    /**
     * See const MIME_TYPE_PDF or MIME_TYPE_IMAGE for an example of MIME type
     * @param array|string[] $mimeTypeAllowed
     */
    public function setMimeTypeAllowed(array $mimeTypeAllowed): void
    {
        $this->mimeTypeAllowed = $mimeTypeAllowed;
    }


    /**
     * Validate the MIME Type of the uploaded file
     */
    private function validateMimeType(): bool
    {
        $fileMimeType = (new finfo())->file($this->file['tmp_name'], FILEINFO_MIME_TYPE);
        if (array_search($fileMimeType, $this->mimeTypeAllowed) === false) {
            $this->errors[] = "Fichier de type incorrect";
            return false;
        }
        return true;
    }


    /**
     * Validate the size of the uploaded file
     * @return bool
     */
    private function validateFileSize(): bool
    {
        if (filesize($this->file['tmp_name']) > $this->maxSize) {
            $this->errors[] = "Taille maximale du fichier depassé (maximum: " . $this->maxSize / 1024 . "KB)";
            return false;
        }
        return true;
    }


    /**
     * return errors generated during file validation and upload
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }


    /**
     * Return the Url of uploaded file
     * @return false|string
     */
    public function getFileUrl()
    {
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $sheme = "https";
        } else {
            $sheme = "http";
        }


        if (isset($this->file['name'])) {
            return $sheme . "://" . $_SERVER['HTTP_HOST'] . $this->uploadDir . "/" . $this->file['name'];
        }
        return false;
    }


    /**
     * Set the maximum size allowed for the uploaded file
     * @param int $maxSize
     */
    public function setMaxSize(int $maxSize): void
    {
        $this->maxSize = $maxSize;
    }

}