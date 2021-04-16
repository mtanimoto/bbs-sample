<?php

namespace App\Libraries;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\ServiceException;
use MicrosoftAzure\Storage\Blob\BlobSharedAccessSignatureHelper;
use MicrosoftAzure\Storage\Common\Internal\Resources;
use CodeIgniter\HTTP\Files\UploadedFile;

class AzureStorageBlob
{
    private $_connectionString;
    private $_accountName;
    private $_accountKey;
    private $_container;
    private $_client;

    public function __construct()
    {
        $this->_connectionString = getenv('azureStoregeBlob.connectionString');
        $this->_accountName = getenv('azureStoregeBlob.accountName');
        $this->_accountKey = getenv('azureStoregeBlob.accountKey');
        $this->_container = getenv('azureStoregeBlob.container');

        $this->_client = BlobRestProxy::createBlobService($this->_connectionString);
    }

    public function uploadBlob(UploadedFile $file, string $folderPath)
    {
        try {
            $path = "{$folderPath}/{$file->getRandomName()}";
            $content = fopen($file->getRealPath(), "r");
            $this->_client->createBlockBlob($this->_container, $path, $content);
            return $path;
        } catch (ServiceException $e) {
            $code = $e->getCode();
            $error_message = $e->getMessage();
            log_message('error', "{$code}: {$error_message}");
            return false;
        }
    }

    public function getBlobUrl(string $fileName): string
    {
        $url = $this->_client->getBlobUrl($this->_container, $fileName);

        $helper = new BlobSharedAccessSignatureHelper($this->_accountName, $this->_accountKey);

        $d = new \Datetime('now', new \DateTimeZone('UTC'));
        $signedResource = Resources::RESOURCE_TYPE_BLOB;
        $resourceName = "{$this->_container}/{$fileName}";
        $signedPermissions = 'r';
        $signedStart = $d->format('Y-m-d\TH:i:s\Z');
        $signedExpiry = $d->modify('+1 hour')->format('Y-m-d\TH:i:s\Z');
        $signedIP = "";
        $signedProtocol = "https";

        $token = $helper->generateBlobServiceSharedAccessSignatureToken(
            $signedResource,
            $resourceName,
            $signedPermissions,
            $signedExpiry,
            $signedStart,
            $signedIP,
            $signedProtocol
        );
        return "{$url}?{$token}";
    }
}
