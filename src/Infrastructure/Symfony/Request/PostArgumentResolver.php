<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Symfony\Request;

use DDDExample\Shared\Extractor\PrimitiveExtractor;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class PostArgumentResolver implements ArgumentValueResolverInterface
{
    private const ARGUMENT_NAME = 'post';

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
        yield PrimitiveExtractor::for($request->request->all());
    }
}
