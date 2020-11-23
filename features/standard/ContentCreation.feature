@richtext
Feature: Content items creation
  As an administrator
  In order to manage content to my site
  I want to create, edit, copy and move content items.

  Background:
      Given I open Login page in admin SiteAccess
      And I am logged as admin
      And I'm on Content view Page for "root"

  @javascript @APIUser:admin @IbexaOSS @IbexaContent @IbexaWeb @IbexaCommerce @test
  Scenario: Content can be previewed during creation
    When I start creating a new content "Folder"
    And I set content fields
      | label | value     |
      | Name  | Test Name |
    And I click on the edit action bar button "Preview"
    And I go to "tablet" preview
    And I go to "mobile" preview
    And I go to "desktop" preview
    And I go back from content preview
    Then I should be on Content Update page for "Test Name"
    And content fields are set
      | label | value     |
      | Name  | Test Name |

  @javascript @common
  Scenario: Content can be published
    When I start creating a new content "Article"
    And I set content fields
      | label | value        |
      | Title | Test Article |
    And I set article main content field to "Test article intro"
    And I click on the edit action bar button "Publish"
    Then success notification that "Content published." appears
    And I should be on content container page "Test Article" of type "Article" in root path
    And content attributes equal
      | label | value        |
      | Title | Test Article |
    And article main content field equals "Test article intro"

  @javascript @common
  Scenario: Content can be edited
    Given I navigate to content "Test Article" of type "Article" in root path
    And I click on the edit action bar button "Edit"
    And I set content fields
      | label | value               |
      | Title | Test Article edited |
    And I set article main content field to "Test Article intro edited"
    And I click on the edit action bar button "Publish"
    Then success notification that "Content published." appears
    And I should be on content container page "Test Article edited" of type "Article" in root path
    And content attributes equal
      | label | value               |
      | Title | Test Article edited |
    And article main content field equals "Test Article intro edited"

  @javascript @common
  Scenario: Content can be previewed during edition
    Given I open UDW and go to "root/Test Article edited"
    When I click on the edit action bar button "Edit"
    And I should be on "Content Update" "Test Article edited" page
    And I click on the edit action bar button "Preview"
    And I go to "tablet" view in "Test Article edited" preview
    And I go to "mobile" view in "Test Article edited" preview
    And I go to "desktop" view in "Test Article edited" preview
    And I go back from content "Test Article edited" preview
    Then I should be on "Content Update" "Test Article edited" page
    And content fields are set
      | label | value               |
      | Title | Test Article edited |
    And article main content field is set to "Test Article intro edited"
