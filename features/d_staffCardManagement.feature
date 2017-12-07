Feature: StaffCardManagement

  Background:
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"

  @javascript
  Scenario: Create new empty card (random)
    Given I follow "New card"
    Then I should be on "/staff/new-card"
    Then I select "123" from "appbundle_card_center"
    And I click on "generate"
    And I wait "4000"
    And I press "submit"
    Then I should be on "/staff/new-card"
    Then I should see "The card has been updated"

  @javascript
  Scenario: Create new empty card manually
    Given I follow "New card"
    Then I should be on "/staff/new-card"
    Then I select "123" from "appbundle_card_center"
    And I fill in "appbundle_card_number" with "68497"
    And I fill in "appbundle_card_modulo" with "3"
    And I wait "4000"
    And I press "submit"
    Then I should be on "/staff/new-card"
    Then I should see "The card has been updated"
    Then I should see "123684973"

  @javascript
  Scenario: Give a card to a customer
    Given I follow "New card"
    And I click on "give-2"
    And I wait "3000"
    Then I should not see an "give-2" element