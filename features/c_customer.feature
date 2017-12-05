Feature: Customer

  Background:
    Given I am on "/login"
    And I fill in "username" with "toto"
    And I fill in "password" with "test"
    And I press "submit"
    Then I should be on "/customer"

  @customer
  Scenario: From login to customer panel
    And the ".customer-infos" element should contain "tata"
    And the ".customer-infos" element should contain "titi"
    And the ".card-infos" element should contain "Customer card"

  @customer
  Scenario: Add a new card on customer panel
    And I fill in "number" with "123456789"
    And I press "submit"
    Then I should be on "/customer"
    And the response status code should be 200
    And the ".alert-success" element should contain "Success ! You have a new card !"
    And I should see "Hey ! You can now use the offer Welcome !"

  @customer
  Scenario: Modify customer infos
    Then I follow "modify"
    Then I should be on "/customer/modify"
    And the response status code should be 200
    And I fill in "appbundle_customer_nickname" with "nicko"
    And I fill in "appbundle_customer_adress" with "rue titi"
    And I fill in "appbundle_customer_phone" with "0123454556"
    And I press "submit"
    Then I should be on "/customer"
    And the response status code should be 200
    And the ".customer-infos" element should contain "nicko"
    And the ".customer-infos" element should contain "0123454556"
    And the ".alert-success" element should contain "Your informations were modified."