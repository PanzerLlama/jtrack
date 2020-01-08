<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/8/20
 * Time: 6:15 PM
 */
declare(strict_types=1);

namespace App\Factory;


use App\Interfaces\TelemetryInterface;
use App\Service\ProjectionService;

class TelemetryCreator
{
    /**
     * @var ProjectionService
     */
    private $projectionService;

    public function __construct(ProjectionService $projectionService)
    {
        $this->projectionService = $projectionService;
    }

    public function create(TelemetryInterface $subject, array $data)
    {
        $projection = $this->projectionService->getProjection($subject);

        var_dump($projection);

    }
}