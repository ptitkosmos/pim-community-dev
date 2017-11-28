@javascript
Feature: Show system information
  In order to inform
  As an administrator
  I need to be able to show system information

  Background:
    Given the "catalog_modeling" catalog configuration
    Given I am logged in as "Peter"
    And I am on the System info page

  Scenario: Successfully display product model information
    Then I should see the text "Product models 12"
    And I should see the text "Variant product 12"
    And I should see the text "Family variant 12"
