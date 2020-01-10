<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/8/20
 * Time: 6:15 PM
 */
declare(strict_types=1);

namespace App\Factory;


use App\Exception\InvalidTelemetryException;
use App\Interfaces\TelemetryInterface;
use App\Service\ProjectionService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TelemetryCreator
{
    /**
     * @var ProjectionService
     */
    private $projectionService;

    /**
     * @var ValidatorInterface
     */
    private $validator;

    public function __construct(ProjectionService $projectionService, ValidatorInterface $validator)
    {
        $this->projectionService    = $projectionService;
        $this->validator            = $validator;
    }

    /**
     * @param TelemetryInterface $subject
     * @param Request $request
     * @return TelemetryInterface
     * @throws InvalidTelemetryException
     */
    public function create(TelemetryInterface $subject, Request $request)
    {
        $projection = $this->projectionService->getProjection($subject);

        foreach ($projection as $k => $v) {
            if (null !== $value = $request->get($k)) {
                $projection[$k] = $value;
            }
        }

        $this->projectionService->updateModelFromProjection($subject, $projection);

        $errors = $this->validator->validate($subject);

        if (count($errors)) {
            throw new InvalidTelemetryException((string) $errors);
        }

        return $subject;
    }
}