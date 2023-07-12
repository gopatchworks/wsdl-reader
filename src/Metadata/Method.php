<?php

declare(strict_types=1);

namespace Soap\WsdlReader\Metadata;

use Soap\Engine\Metadata\Collection\ParameterCollection;
use Soap\Engine\Metadata\Model\MethodMeta;
use Soap\Engine\Metadata\Model\XsdType;
use Soap\WsdlReader\Model\Definitions\Parts;

final class Method
{
    private ParameterCollection $parameters;
    private Parts $headers;
    private string $name;
    private XsdType $returnType;
    private MethodMeta $meta;

    public function __construct(string $name, Parts $headers, ParameterCollection $parameters, XsdType $returnType)
    {
        $this->name = $name;
        $this->returnType = $returnType;
        $this->headers = $headers;
        $this->parameters = $parameters;
        $this->meta = new MethodMeta();
    }

    public function getParameters(): ParameterCollection
    {
        return $this->parameters;
    }

    public function getHeaders(): Parts
    {
        return $this->headers;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getReturnType(): XsdType
    {
        return $this->returnType;
    }

    public function getMeta(): MethodMeta
    {
        return $this->meta;
    }

    /**
     * @param callable(MethodMeta): MethodMeta $metaProvider
     */
    public function withMeta(callable $metaProvider): self
    {
        $new = clone $this;
        $new->meta = $metaProvider($this->meta);

        return $new;
    }
}
