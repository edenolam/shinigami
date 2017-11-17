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
    And I fill in "search_field" with "897987979"
    And I press "search"
    Then I should be on "/staff/card/897987979"
    And the response status code should be 200
    And I should see "card customer-infos col s3"

  @staff
  Scenario: Edit profile customer
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I am on "/staff/card/897987979"
    And the response status code should be 200
    And I follow "modify-profile"
    Then I should be on "/staff/card/897987979/editcustomer"
    And the response status code should be 200
    And the "appbundle_customer_firstname" field should contain "julien"
    And the "appbundle_customer_lastname" field should contain "basquin"
    And the "appbundle_customer_nickname" field should contain "super killer"


