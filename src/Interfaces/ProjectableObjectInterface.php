<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/4/20
 * Time: 2:40 PM
 */
declare(strict_types=1);

namespace App\Interfaces;


interface ProjectableObjectInterface
{
    public function getProjectionClass(): ProjectionInterface;
}