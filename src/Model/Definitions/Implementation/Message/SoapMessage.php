<?php
declare(strict_types=1);

namespace Soap\WsdlReader\Model\Definitions\Implementation\Message;

use DOMElement;
use Soap\WsdlReader\Model\Definitions\BindingUse;

final class SoapMessage implements MessageImplementation
{
    public function __construct(
        public readonly BindingUse $bindingUse,
        public readonly ?DOMElement $header = null
    ) {
    }
}
