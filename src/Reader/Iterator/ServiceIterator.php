<?php

declare(strict_types=1);

namespace Soap\WsdlReader\Reader\Iterator;

use Soap\WsdlReader\Xml\Xpath\XpathProvider;
use VeeWee\Xml\Dom\Document;
use Psl\Type;

class ServiceIterator implements \IteratorAggregate
{
    private Document $wsdl;

    public function __construct(Document $wsdl)
    {
        $this->wsdl = $wsdl;
    }

    public function getIterator(): \Generator
    {
        $xpath = XpathProvider::provide($this->wsdl);

        yield from array_reduce(
            [...$this->wsdl->xpath()->query('/wsdl:definitions/wsdl:service')],
            fn(array $services, \DOMElement $service): array => array_merge(
                $services,
                [
                    $service->getAttribute('name') => [
                        'name' => $service->getAttribute('name'),
                        'port' => [
                            'name' => $xpath->evaluate('string(./wsdl:port/@name)',Type\string(), $service),
                            'binding' => $xpath->evaluate('string(./wsdl:port/@binding)',Type\string(), $service),
                        ],
                        'address' => [
                            'location' => $xpath->evaluate('string(./wsdl:port/soap:address/@location)',Type\string(), $service)
                        ],
                    ]
                ]
            ),
            []
        );
    }
}