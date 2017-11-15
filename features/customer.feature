Feature: Customer
  @customer
  Scenario: From login to customer panel
    Given I am on "/login"
    And I fill in "username" with "toto"
    And I fill in "password" with "test"
    And I press "submit"
    Then I should be on "/customer"
    And the response status code should be 200
    And I should see "tata" in the ".customer-infos" element
    And I should see "titi" in the ".customer-infos" element
    And I should see "My card" in the ".card-infos" element
    And I should see "My offers" in the ".offers-info" element