<?php
namespace App\Api;

use PhalApi\Filter;
use PhalApi\Exception\BadRequestException;

/**
 * token验证
 */
class Common implements Filter
{
    public function check()
    {
        $signature = \PhalApi\DI()->request->getAll();
        $token=$signature['Token'];
        $Token = \PhalApi\DI()->cache->get($token);
        if (!$Token) {
            throw new BadRequestException('token验证失败', 1);
        }
    }
}