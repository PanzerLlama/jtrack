<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/10/20
 * Time: 5:26 PM
 */
declare(strict_types=1);

namespace App\Serializer;


use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

class ArrayEncoder implements EncoderInterface, DecoderInterface
{
    public function encode($data, $format, array $context = [])
    {
        return serialize($data);
    }

    public function supportsEncoding($format)
    {
        return 'array' === $format;
    }

    public function decode($data, $format, array $context = [])
    {
        return unserialize($data);
    }

    public function supportsDecoding($format)
    {
        return 'array' === $format;
    }
}