Feature: Staff
  @staff
  Scenario: From login to staff panel
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"
    And the response status code should be 200
    And I should see "newsletters" in the "nav" element
    And I should see "offers" in the "nav" element
    And I should see ".search_by_number" in the ".form_staff" element
    And I should see ".search_by_name" in the ".form_staff" element