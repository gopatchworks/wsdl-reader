--TEST--
SOAP XML Schema 70: Attribute with default value (attributeGroup)
--FILE--
<?php
include __DIR__."/test_schema.inc";
$schema = <<<EOF
    <complexType name="testType">
        <attribute name="str" type="string"/>
        <attributeGroup ref="tns:int_group"/>
    </complexType>
    <attributeGroup name="int_group">
        <attribute name="int" type="int" default="5"/>
    </attributeGroup>
EOF;
test_schema($schema,'type="tns:testType"');
?>
--EXPECTF--
Methods:
  > test(testType $testParam): void

Types:
  > http://test-uri/:testType {
    @string $str
    @int $int
  }
