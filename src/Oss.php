<?php

namespace Es3\Utility;

use App\Constant\AppConst;
use EasySwoole\Http\Message\UploadFile;
use Es3\Exception\InfoException;
use Es3\Exception\WaringException;
use OSS\OssClient;

/**
 * Class Oss
 * @package Es3\Utility
 */
class Oss
{
    /**
     * @param $file 上传的文件
     * @param string $directory 对应的目录
     * @param string $accessId
     * @param string $accessKey
     * @param string $endpoint
     * @throws \OSS\Core\OssException
     */
    static function upload(UploadFile $file, string $directory, string $bucket, string $accessId, string $accessKey, string $endpoint): array
    {
        $ossClient = new OssClient($accessId, $accessKey, $endpoint);

        /** 原始文件 */
        $original = $file->getClientFilename();

        // 截取上传原始文件信息
        $directory = trim($directory, '/');
        $ext = pathinfo($original, PATHINFO_EXTENSION);
        $name = pathinfo($original, PATHINFO_FILENAME);

        $newName = date('YmdHis') . rand(0, 10000) . '.' . $ext;
        $location = "{$directory}/{$newName}";
        $filePath = $file->getTempName();

        //上传图片
        $result = $ossClient->uploadFile($bucket, $location, $filePath);
        if (false === $result) {
            throw new WaringException(7501, 'Oss文件上传失败');
        }

        $oss = [
            'system_code' => AppConst::SYSTEM_CODE,
            'hash' => $result['x-oss-hash-crc64ecma'] ?? '',
            'host' => $result['oss-requestheaders']['Host'] ?? '',
            'original' => $file->getClientFilename(),
            'location' => $location,
            'ext' => $ext,
            'http' => 'http://' . $result['oss-requestheaders']['Host'] . '/' . $location,
            'https' => 'https://' . $result['oss-requestheaders']['Host'] . '/' . $location,
        ];

        return $oss;
    }
}