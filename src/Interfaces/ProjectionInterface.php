<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/4/20
 * Time: 2:23 PM
 */
declare(strict_types=1);

namespace App\Interfaces;


interface ProjectionInterface
{
    public function getSubject(): ProjectableObjectInterface;
}