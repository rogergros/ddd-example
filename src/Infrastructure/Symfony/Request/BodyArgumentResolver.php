<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Symfony\Request;

use DDDExample\Shared\Extractor\PrimitiveExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class BodyArgumentResolver implements ArgumentValueResolverInterface
{
    private const ARGUMENT_NAME = 'body';

    public function __construct(private BodyContentDecoder $contentDeserializer)
    {
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return
            $argument->getType() === PrimitiveExtractor::class
            && $argument->getName() === self::ARGUMENT_NAME;
    }

    /**
     * @return iterable<PrimitiveExtractor>
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $content = $this->contentDeserializer->decode($request);

        yield PrimitiveExtractor::for($content);
    }
}
