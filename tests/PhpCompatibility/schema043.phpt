--TEST--
SOAP XML Schema 43: Extension of simple type (2)
--FILE--
<?php
include __DIR__."/test_schema.inc";
$schema = <<<EOF
    <complexType name="testType2">
        <simpleContent>
            <extension base="int">
                <attribute name="int" type="int"/>
            </extension>
        </simpleContent>
    </complexType>
    <complexType name="testType">
        <simpleContent>
            <extension base="tns:testType2">
                <attribute name="int2" type="int"/>
            </extension>
        </simpleContent>
    </complexType>
EOF;
test_schema($schema,'type="tns:testType"');
?>
--EXPECTF--
Methods:
  > test(testType $testParam): void

Types:
  > http://test-uri/:testType2 extends integer {
    int $_
    @int $int
  }
  > http://test-uri/:testType extends testType2 {
    int $_
    @int $int
    @int $int2
  }
