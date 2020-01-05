<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/4/20
 * Time: 3:37 PM
 */
declare(strict_types=1);

namespace App\Model\Projection;


use App\Interfaces\ProjectableObjectInterface;
use App\Interfaces\ProjectionInterface;

class GenericProjection implements ProjectionInterface
{
    /**
     * @var ProjectableObjectInterface
     */
    private $subject;

    public function __construct(ProjectableObjectInterface $subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return ProjectableObjectInterface
     */
    public function getSubject(): ProjectableObjectInterface
    {
        return $this->subject;
    }

    /**
     * @param ProjectableObjectInterface $subject
     * @return GenericProjection
     */
    public function setSubject(ProjectableObjectInterface $subject): GenericProjection
    {
        $this->subject = $subject;
        return $this;
    }
}