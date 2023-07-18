<?php
declare(strict_types=1);

namespace Soap\WsdlReader\Model\Definitions\Implementation\Message;

use DOMElement;
use Soap\WsdlReader\Model\Definitions\BindingUse;
use Soap\WsdlReader\Model\Definitions\QNamed;

final class SoapMessage implements MessageImplementation
{
    public function __construct(
        public readonly BindingUse $bindingUse,
        public readonly ?QNamed $namespace = null,
        public readonly ?DOMElement $header = null
    ) {
    }
}
