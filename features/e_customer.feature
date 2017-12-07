Feature: Customer

  Background:
    Given I am on "/login"
    And I fill in "username" with "toto"
    And I fill in "password" with "test"
    And I press "submit"
    Then I should be on "/customer"

  @javascript
  Scenario: From login to customer panel
    And the ".customer-infos" element should contain "tata"
    And the ".customer-infos" element should contain "titi"
    And the ".card-infos" element should contain "Customer card"

  @javascript
  Scenario: Add a new card on customer panel
    And I fill in "number" with "123684973"
    And I press "submit"
    Then I should be on "/customer"
    And the ".alert-success" element should contain "Success ! You have a new card !"
    And I should see "Hey ! You can now use the offer Welcome !"
    And I should see "123684973"

  @javascript
  Scenario: Modify customer infos
    Then I follow "modify-profile"
    Then I should be on "/customer/modify"
    And I fill in "appbundle_customer_nickname" with "red"
    And I fill in "appbundle_customer_adress" with "rue titi"
    And I fill in "appbundle_customer_city" with "Jadielle"
    And I press "submit"
    Then I should be on "/customer"
    And the ".customer-infos" element should contain "red"
    And the ".customer-infos" element should contain "rue titi"
    And the ".customer-infos" element should contain "Jadielle"
    And the ".alert-success" element should contain "Your informations were modified."



