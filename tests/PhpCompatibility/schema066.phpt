--TEST--
SOAP XML Schema 66: Attribute with fixed value (1)
--FILE--
<?php
include __DIR__."/test_schema.inc";
$schema = <<<EOF
    <complexType name="testType">
        <attribute name="str" type="string"/>
        <attribute name="int" type="int" fixed="5"/>
    </complexType>
EOF;
test_schema($schema,'type="tns:testType"');
?>
--EXPECTF--
Methods:
  > test(testType $testParam): void

Types:
  > http://test-uri/:testType
