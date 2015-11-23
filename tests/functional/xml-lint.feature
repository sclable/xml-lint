Feature: Check an xml file whether its structure seems to be ok or not.

  Scenario: Check and confirm a file to be ok
    Given the file "fourtytwo.xml"
    When I run lint
    Then I have a return code "0"

  Scenario: Check a file with a missing close tag
    Given the file "broken.xml"
    When I run lint
    Then I have a return code "1"

  Scenario: Check a file with an xsd schema validation
    Given the file "with_xsd_broken.xml"
    When I run lint
    Then I have a return code "1"

