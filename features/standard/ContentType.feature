Feature: Content types management
  As an administrator
  In order to customize my eZ installation
  I want to manage my Content types.

  Background:
    Given I open Login page in admin SiteAccess
    And I am logged as admin

  @javascript @IbexaOSS @IbexaContent @IbexaExperience @IbexaCommerce
  Scenario: Changes can be discarded while creating Content Type
    Given I'm on Content Type Page for "Content" group
    When I create a new Content Type
      And I set fields
      | label      | value                     |
      | Name       | Test Content Type         |
      | Identifier | TestContentTypeIdentifier |
      And I click on the edit action bar button "Discard changes"
    Then I should be on Content Type group page for "Content" group
      And there's no "Test Content Type" on Content Types list

  @javascript @IbexaOSS @IbexaContent @IbexaExperience @IbexaCommerce
  Scenario: New Content Type can be added to Content Type group
    Given I'm on Content Type Page for "Content" group
    When I create a new Content Type
      And I set fields
        | label                | value                     |
        | Name                 | Test Content Type         |
        | Identifier           | TestContentTypeIdentifier |
        | Content name pattern | <name>                    |
      And I add field "Country" to Content Type definition
      And I set "Name" to "Country field" for "Country" field
      And I click on the edit action bar button "Save"
    Then notification that "Content Type" "Test Content Type" is updated appears
    Then I should be on Content Type page for "Test Content Type"
      And Content Type has proper Global properties
        | label                | value                     |
        | Name                 | Test Content Type         |
        | Identifier           | TestContentTypeIdentifier |
        | Content name pattern | <name>                    |
      And Content Type "Test Content Type" has proper fields
        | fieldName      | fieldType |
        | CountryField   | ezcountry |

  @javascript @IbexaOSS @IbexaContent @IbexaExperience @IbexaCommerce
  Scenario: Changes can be discarded while editing Content type
    Given I create a "TestDiscard CT" Content Type in "Content" with "testdiscard" identifier
      | Field Type  | Name        | Identifier          | Required | Searchable | Translatable | Settings       |
      | Text line   | Name        | name	            | no      | yes	      | yes          |                  |
    And I'm on Content Type Page for "Content" group
    And there's a "TestDiscard CT" on Content Types list
    When I start editing Content Type "TestDiscard CT"
      And I set fields
        | label | value                    |
        | Name  | Test Content Type edited |
      And I click on the edit action bar button "Discard changes"
    Then I should be on Content Type group page for "Content" group
      And there's a "TestDiscard CT" on Content Types list
      And there's no "TestDiscard CT" on Content Types list

  @javascript @common
  Scenario: New Field can be added while editing Content Type
    Given I create a "TestEdit CT" Content Type in "Content" with "testedit" identifier
      | Field Type  | Name        | Identifier          | Required | Searchable | Translatable | Settings       |
      | Text line   | Name        | name	            | no      | yes	      | yes          |                  |
    And I'm on Content Type Page for "Content" group
    When I start editing Content Type "TestEdit CT"
      And I set fields
        | label | value                    |
        | Name  | Test Content Type edited |
      And I add field "Date" to Content Type definition
    And I set "Name" to "DateField" for "Date" field
      And I click on the edit action bar button "Save"
    Then success notification that "Content Type 'Test Content Type edited.' is updated" appears
    Then I should be on Content Type page for "Test Content Type edited" group
      And Content Type has proper Global properties
        | label                | value                     |
        | Name                 | Test Content Type edited  |
        | Identifier           | TestContentTypeIdentifier |
        | Content name pattern | <name>                    |
      And Content Type "Test Content Type" has proper fields
        | fieldName      | fieldType |
        | CountryField   | ezcountry |
        | DateField      | ezdate    |

  @javascript @common
  Scenario: Content type can be deleted from Content Type group
    Given I create a "TestDelete CT" Content Type in "Content" with "testdelete" identifier
      | Field Type  | Name        | Identifier          | Required | Searchable | Translatable | Settings       |
      | Text line   | Name        | name	            | no      | yes	      | yes          |                  |
    And I'm on Content Type Page for "Content" group
    And there's a "TestDiscard CT" on Content Types list
    When I delete "TestDelete CT" Content Type
    Then success notification that "Content Type 'Test Content Type edited' is deleted." appears
    And there's no "TestDelete CT" on Content Types list
