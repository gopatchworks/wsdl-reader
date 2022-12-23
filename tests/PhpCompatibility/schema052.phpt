--TEST--
SOAP XML Schema 52: Array in complex type (maxOccurs > 1, array with one value)
--FILE--
<?php
include __DIR__."/test_schema.inc";
$schema = <<<EOF
    <complexType name="testType">
        <sequence>
            <element name="int" type="int"/>
            <element name="int2" type="int" maxOccurs="unbounded"/>
        </sequence>
    </complexType>
EOF;
test_schema($schema,'type="tns:testType"');
?>
--EXPECTF--
Methods:
  > test(testType $testParam): void

Types:
  > http://test-uri/:testType
