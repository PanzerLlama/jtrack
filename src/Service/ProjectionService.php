<?php
/**
 * Created by PhpStorm.
 * User: Lech Buszczynski <lecho@phatcat.eu>
 * Date: 1/4/20
 * Time: 2:21 PM
 */
declare(strict_types=1);

namespace App\Service;


use App\Annotations\Projection;
use Doctrine\Common\Annotations\AnnotationReader;

class ProjectionService
{
    /**
     * @param $subject
     * @param array $groups
     * @return array
     */
    public function getProjection($subject, array $groups = []): array
    {
        $projection = [];

        $reader = new AnnotationReader();

        $reflectionClass = new \ReflectionClass($subject);

        foreach ($reflectionClass->getProperties() as $property) {

            $propertyName = $property->getName();
            $annotation = $reader->getPropertyAnnotation(
                $property,
                Projection::class
            );

            if ($annotation) {
                $getter = 'get'.ucfirst($propertyName);

                if (!method_exists($subject, $getter)) {
                    $getter = 'is'.ucfirst($propertyName);
                }

                $projection[$propertyName] = $subject->$getter();
            }
        }

        return $projection;
    }

    public function updateModelFromProjection($subject, array $projection, ?array $groups = [])
    {
        $reader = new AnnotationReader();

        $reflectionClass = new \ReflectionClass($subject);

        foreach ($reflectionClass->getProperties() as $property) {

            $propertyName = $property->getName();

            /** @var Projection $annotation */
            $annotation = $reader->getPropertyAnnotation(
                $property,
                Projection::class
            );

            if ($annotation) {
                $setter = 'set'.ucfirst($propertyName);
                if (isset($projection[$propertyName])) {

                    if ($annotation->getType() === 'integer') {
                        $projection[$propertyName] = (integer) $projection[$propertyName];
                    } elseif ($annotation->getType() === 'float') {
                        $projection[$propertyName] = (float) $projection[$propertyName];
                    }

                    $subject->$setter($projection[$propertyName]);
                }
            }
        }
    }
}