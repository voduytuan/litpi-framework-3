<?php

namespace Litpi;

class Uploader
{
    const ERROR_UPLOAD_OK = 0;
    const ERROR_UPLOAD_UNKNOWN = 1;
    const ERROR_FILESIZE = 2;
    const ERROR_FILETYPE = 4;
    const ERROR_PERMISSION = 8;

    public $directory;
    public $sourceName;
    public $sourceTmpFile;
    public $destination;
    public $validType;
    public $inValidType;
    public $maxFileSize;
    public $isUploadFile = true;
    public $usingRenameFunction = false;

    public function __construct($sourceTmpFile = '', $sourceName = '', $desitnationDirectory = '', $destinationName = '', $validType = null, $inValidType = null, $fileSizeInByte = 0)
    {
        $this->sourceTmpFile = $sourceTmpFile;
        $this->sourceName = $sourceName;
        $this->directory = $this->addDirSeparator($desitnationDirectory);
        $this->destination = $destinationName;
        $this->validType = empty($validType)?array('JPG','JPEG','PNG','GIF'):$validType;
        $this->inValidType = empty($inValidType)?array():$inValidType;
        $this->maxFileSize = (int) ($fileSizeInByte) > 0 ? $this->returnByte($fileSizeInByte) : $this->returnByte(ini_get('upload_max_filesize'));
    }

    public function upload($overwrite = false, &$finalName = '')
    {
        $error = 0;
        //check file size
        if (filesize($this->sourceTmpFile) > $this->maxFileSize) {
            $error = $error | self::ERROR_FILESIZE;
        }

        //check file type
        $sourceExt = strtoupper(substr(strrchr($this->sourceName, '.'), 1));
        if (!in_array($sourceExt, $this->validType) || in_array($sourceExt, $this->inValidType)) {
            $error = $error | self::ERROR_FILETYPE;
        }

        //check file before process
        if (file_exists($this->directory) && !is_writable($this->directory)) {
            $error = $error | self::ERROR_PERMISSION;
        }

        if ($error == 0) {
            $newName = $this->sourceName;
            if (strlen($this->destination) > 0) {
                $newName = $this->destination;
            }

            //tien hanh xu ly file se upload
            //$name=ereg_replace("[^a-zA-Z0-9_.]","-", $newName);
            $name=preg_replace("/[^a-zA-Z0-9_.]/ms", "-", $newName);

            //find namepart and extension part
            $pos = strrpos($name, '.');
            if ($pos === false) {
                $pos = strlen($name);
            }
            $namePart = substr($name, 0, $pos);
            $extPart = substr(strrchr($name, '.'), 1);

            if (!$overwrite) {
                while (file_exists($this->directory.$namePart.".".$extPart)) {
                    $namePart .= "-new";
                }
            }

            $finalName = $namePart.".".$extPart;
            $destinationPath = $this->directory.$namePart.".".$extPart;

            //create destination directory if not exists
            if (!file_exists($this->directory)) {
                mkdir($this->directory, 0777, true);
            }

            if ($this->isUploadFile) {
                if ($this->usingRenameFunction) {
                    if (!rename($this->sourceTmpFile, $destinationPath)) {
                        $error = $error | self::ERROR_UPLOAD_UNKNOWN;
                    } else {
                        $error = $error | self::ERROR_UPLOAD_OK;
                    }
                } else {
                    if (!move_uploaded_file($this->sourceTmpFile, $destinationPath)) {
                        $error = $error | self::ERROR_UPLOAD_UNKNOWN;
                    } else {
                        $error = $error | self::ERROR_UPLOAD_OK;
                    }
                }
            } else {
                if (!rename($this->sourceTmpFile, $destinationPath)) {
                    $error = $error | self::ERROR_UPLOAD_UNKNOWN;
                } else {
                    $error = $error | self::ERROR_UPLOAD_OK;
                }
            }
        }
        return $error;
    }

    public function returnByte($val)
    {
        $val = trim($val);
        $last = strtolower($val[strlen($val)-1]);
        switch ($last) {
            // The 'G' modifier is available since PHP 5.1.0
            case 'g':
                $val *= 1024; //intension
            case 'm':
                $val *= 1024; //intension
            case 'k':
                $val *= 1024;
        }

        return $val;
    }

    public function addDirSeparator($directory)
    {
        $last = $directory[strlen($directory)-1];
        if ($last != '/' && $last !=  '\\') {
            $directory .= DIRECTORY_SEPARATOR;
        }

        return $directory;
    }
}
