<?php

namespace App\Normalizer;

use App\Entity\Product;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\Serializer\Exception\LogicException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\SerializerAwareTrait;

class ProductNormalizer implements NormalizerInterface, DenormalizerInterface, SerializerAwareInterface
{
    use SerializerAwareTrait;

    /**
     * @var RegistryInterface
     */
    private $doctrine;

    public function __construct(RegistryInterface $registry)
    {
        $this->doctrine = $registry;
    }

    public function normalize($object, $format = null, array $context = [])
    {
        /** @var Product $entity */
        $entity = &$object;
        $json = new \ArrayObject(
            [
                'id'    => $entity->getId(),
                'name'  => $entity->getName(),
                'image' => $entity->getImage(),
            ]
        );

        if (!$this->serializer instanceof NormalizerInterface) {
            throw new LogicException(
                'Cannot normalize attributes because injected serializer is not a normalizer'
            );
        }

        return $this->serializer->normalize(
            $json,
            $format,
            array_merge($context, [])
        );
    }

    public function denormalize($data, $class, $format = null, array $context = [])
    {
        if (array_key_exists('new', $context) && $context['new']) {
            if (!isset($data['name']) || !isset($data['image'])) {
                throw new \Exception('Invalid keys data');
            }

            $entity = new Product();
        } elseif (array_key_exists('object', $context) && $context['object']) {
            $entity = $context['object'];
        } else {
            try {
                /** @var Product $entity */
                $entity = $this->doctrine->getManager()
                    ->getRepository(Product::class)
                    ->find($context['id']);

            } catch (\Exception $e) {
                throw new LogicException(
                    $e->getMessage()
                );
            }
        }

        if (isset($data['name']) && $data['name']) {
            $entity->setName($data['name']);
        }

        if (isset($data['image']) && $data['image']) {
            $entity->setImage($data['image']);
        }

        return $entity;
    }

    public function supportsNormalization($data, $format = null)
    {
        return $data instanceof Product;
    }

    public function supportsDenormalization($data, $type, $format = null)
    {
        return Product::class == $type;
    }
}
