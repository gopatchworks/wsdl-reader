<?php
declare(strict_types=1);

namespace Soap\WsdlReader\Metadata\Converter\Methods\Configurator;

use Soap\WsdlReader\Metadata\Method;
use Soap\Engine\Metadata\Model\MethodMeta;
use Soap\WsdlReader\Model\Definitions\BindingOperation;
use Soap\WsdlReader\Model\Definitions\BindingOperationMessage;
use Soap\WsdlReader\Model\Definitions\Implementation\Message\SoapMessage;
use Soap\WsdlReader\Model\Definitions\Implementation\Operation\SoapOperation;

final class BindingOperationConfigurator
{
    public function __invoke(Method $method, BindingOperation $operation): Method
    {
        $implementation = $operation->implementation;
        if (!$implementation instanceof SoapOperation) {
            return $method;
        }

        return $method->withMeta(
            fn (MethodMeta $meta): MethodMeta => $meta
            ->withSoapVersion($implementation->version->value)
            ->withAction($implementation->action)
            ->withBindingStyle($implementation->style->value)
            ->withInputBindingUsage($this->collectBindingUsageForMessage($operation->input))
            ->withOutputBindingUsage($this->collectBindingUsageForMessage($operation->output))
        );
    }

    private function collectBindingUsageForMessage(?BindingOperationMessage $message): ?string
    {
        $implementation = $message?->implementation;
        if (!$implementation instanceof SoapMessage) {
            return null;
        }

        return $implementation->bindingUse->value;
    }
}
