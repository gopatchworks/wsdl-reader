<?php

declare(strict_types=1);

namespace Soap\WsdlReader\Schema;

use GoetasWebservices\XML\XSDReader\Schema\Attribute\Attribute;
use GoetasWebservices\XML\XSDReader\Schema\Attribute\AttributeContainer;
use GoetasWebservices\XML\XSDReader\Schema\Element\Element;
use GoetasWebservices\XML\XSDReader\Schema\Element\ElementContainer;
use GoetasWebservices\XML\XSDReader\Schema\Element\ElementDef;
use GoetasWebservices\XML\XSDReader\Schema\Schema;
use GoetasWebservices\XML\XSDReader\Schema\Type\Type;
use Soap\WsdlReader\Metadata\Model\Property;
use Soap\WsdlReader\Metadata\Model\Type as SoapType;
use Soap\WsdlReader\Metadata\Model\XsdType;

class TypeProvider
{
    public function forSchema(Schema $schema): \Generator
    {
        foreach ($schema->getTypes() as $type) {
            yield $this->parseFromType($type);
        }

        foreach ($schema->getElements() as $element) {
            yield dump($this->parseFromElement($element));
        }

        foreach ($schema->getSchemas() as $internalSchema) {
            if ($internalSchema === $schema || $internalSchema->getTargetNamespace() === 'http://www.w3.org/2001/XMLSchema') {
                continue;
            }

            yield from $this->forSchema($internalSchema);
        }
    }

    private function parseFromType(Type $type): SoapType
    {
        $restrictions = $type->getRestriction();
        $parent = $type->getParent();
        $extension = $type->getExtension();

        return new SoapType(
            (new XsdType($type->getName()))
                ->withMeta([
                    'abstract' => $type->isAbstract(),
                    'restrictions' => $restrictions ? $restrictions->getChecks() : [],
                    'parent' => $parent ? $parent->getChecks() : [],
                    'extension' => $extension ? $extension->getBase()->getName() : '',
                ])
                ->withXmlNamespace($type->getSchema()->getTargetNamespace()),
            [...$this->parseProperties($type)]
        );
    }


    private function parseProperties(Type $type): \Generator
    {
        $elements = $type instanceof ElementContainer ? $type->getElements() : [];
        if ($elements) {
            /** @var Element $element */
            foreach ($elements as $element) {
                yield new Property(
                    $element->getName(),
                    (new XsdType($element->getType()->getName()))
                        ->withXmlNamespace($element->getSchema()->getTargetNamespace())
                        ->withMeta([
                            'min' => $element->getMin(),
                            'max' => $element->getMax(),
                            'default' => $element->getDefault(),
                            'docs' => $element->getDoc(),
                            // 'type' => $element->getType()
                        ])
                );
            }

            return;
        }


        $attributes = $type instanceof AttributeContainer ? $type->getAttributes() : [];
        if ($attributes) {
            // The content of the type:
            yield new Property('_', new XsdType('todo'));

            /** @var Attribute $attribute */
            foreach ($attributes as $attribute) {
                $attributeType = $attribute->getType();
                yield new Property(
                    $attribute->getName(),
                    (new XsdType($attributeType->getName()))
                        ->withXmlNamespace($attribute->getSchema()->getTargetNamespace())
                        ->withMeta([
                            'use' => $attribute->getUse(),
                            'docs' => $attribute->getDoc(),
                            'default' => $attribute->getDefault(),
                            'fixed' => $attribute->getFixed(),
                            'restrictions' => $attributeType->getRestriction() ? $attributeType->getRestriction()->getChecks() : [],
                        ])
                );
            }
            return;
        }
    }

    private function parseFromElement(ElementDef $element): SoapType
    {
        return new SoapType(
            (new XsdType($element->getName()))
                ->withMeta([
                    'docs' =>$element->getDoc(),
                    'abstract' => $element->getType() && $element->getType()->isAbstract(),
                    // TODO :
                    // 'extension' => $element->getType()->getExtension()->getBase()->getName(),
                    // 'resitriction'
                    // $element->getType()->getRestriction() && $element->getType()->getRestriction()->getChecks();
                ])
                ->withXmlNamespace($element->getSchema()->getTargetNamespace()),
            [...$this->parseProperties($element->getType())]
        );
    }
}