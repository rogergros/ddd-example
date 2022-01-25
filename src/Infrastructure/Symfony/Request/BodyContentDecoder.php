<?php

declare(strict_types=1);

namespace DDDExample\Infrastructure\Symfony\Request;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Serializer\Encoder\DecoderInterface;

final class BodyContentDecoder
{
    /**
     * @var list<string>
     */
    private array $acceptedContentFormats = ['json'];

    public function __construct(
        private DecoderInterface $decoder
    ) {
    }

    /**
     * @return array<mixed>
     */
    public function decode(Request $request): array
    {
        $decoderFormat = $this->decoderFormat($request);
        /** @var string $content */
        $content = $request->getContent();

        $decoded = $this->decoder->decode($content, $decoderFormat);

        if (!is_array($decoded)) {
            throw new BadRequestHttpException('The body content is expected to be an object');
        }

        return $decoded;
    }

    private function decoderFormat(Request $request): string
    {
        $contentType = $request->getContentType();

        if (is_null($contentType) || !in_array($contentType, $this->acceptedContentFormats, true)) {
            throw new BadRequestHttpException('Unsupported content type');
        }

        return $contentType;
    }
}
