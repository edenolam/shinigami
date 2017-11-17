Feature: Staff
  @staff
  Scenario: From login to staff panel
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"
    And the response status code should be 200
    And I should see "newsletter" in the "nav" element
    And I should see "offer" in the "nav" element
    And I should see "search by card"

  @staff
  Scenario: Search customer by card
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"
    And the response status code should be 200
    And I fill in "search_field" with "123456"
    And I press "search"
    Then I should be on "/staff/card/123456"
    And the response status code should be 200
    And the ".firstname" element should contain "julien"
    And the ".lastname" element should contain "basquin"
    And the ".nickname" element should contain "staff"
