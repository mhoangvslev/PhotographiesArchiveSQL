<?php
/**
 * Created by PhpStorm.
 * User: minhhoangdang
 * Date: 22/02/19
 * Time: 00:00
 */

namespace App\Entity;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class EntityDataTransformer implements DataTransformerInterface
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Transforms an Entity to a string (number).
     *
     * @param  Issue|null $issue
     * @return string
     */
    public function transform($entity)
    {
        if (null === $entity) {
            return '';
        }
        return $entity->getId();
    }

    /**
     * Transforms a string (number) to an object (issue).
     *
     * @param  string $issueNumber
     * @return Issue|null
     * @throws TransformationFailedException if object (issue) is not found.
     */
    public function reverseTransform($id)
    {
        // no issue number? It's optional, so that's ok
        if (!id) {
            return;
        }

        $entity = $this->entityManager
            ->getRepository(GenericEntity::class)
            ->find($id);

        if (null === $entity) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An issue with number "%s" does not exist!',
                $id
            ));
        }

        return $entity;
    }
}