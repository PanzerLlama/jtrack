<?php
namespace App\Form\Transformer;


use App\Entity\Tracker;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class TrackerProjectionToIdTransformer implements DataTransformerInterface
{
    /**
     * @var EntityManagerInterface
     */
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager)
	{
		$this->entityManager = $entityManager;
	}

    /**
     * @param mixed $projection
     * @return mixed|null
     */
	public function transform($projection)
    {
        if (!$projection || !isset($projection['id'])) {
			return null;
		}

		return $projection['id'];
	}

    /**
     * @param mixed $id
     * @return mixed|null
     */
	public function reverseTransform($id)
	{
        if (!$id) {
            return null;
        }

        $entity = $this->entityManager->getRepository(Tracker::class)->findOneById($id);

        if (!$entity) {
            throw new TransformationFailedException(sprintf('Tracker with id "%s" does not exist.', $id));
        }

        return $entity;
	}	
}
