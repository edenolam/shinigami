Feature: StaffCustomerManagement

  Background:
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"


  @javascripts
  Scenario: Search customer by card number
    And I fill in "search_field" with "123684973"
    And I press "Search"
    Then I should be on "/staff/customer/123684973"
    And I should see "titi"
    And I should see "tata"
    And I should see "red"

  @javascripts
  Scenario: Search customer by name
    And I click on "tab-byname"
    And I wait "1000"
    And I fill in "search_customer_firstname" with "titi"
    And I fill in "search_customer_lastname" with "tata"
    And I fill in "search_customer_phone" with "0123456789"
    And I press "search"
    Then I should be on "/staff/customer/123684973"
    And I should see "titi"
    And I should see "tata"
    And I should see "red"

  @javascript
  Scenario: Modify customer infos
    And I am on "/staff/customer/123684973"
    Then I follow "modify-profile"
    Then I should be on "/staff/customer/123684973/editcustomer"
    And I fill in "appbundle_customer_adress" with "route victoire"
    And I fill in "appbundle_customer_city" with "Safrania"
    And I press "submit"
    Then I should be on "/staff/customer/123684973"
    And the ".customer-infos" element should contain "red"
    And the ".customer-infos" element should contain "route victoire"
    And the ".customer-infos" element should contain "Safrania"
    And the ".alert-success" element should contain "The customer's informations were modified."

  @javascript
  Scenario: Attribution of a new card to a customer who lost his card
    Given I follow "New card"
    Then I should be on "/staff/new-card"
    Then I select "123" from "appbundle_card_center"
    And I fill in "appbundle_card_number" with "58436"
    And I fill in "appbundle_card_modulo" with "6"
    And I wait "4000"
    And I press "submit"
    Then I should be on "/staff/new-card"
    Then I should see "The card has been updated"
    Then I should see "123584366"
    Then I follow "search"
    And I click on "tab-byname"
    And I wait "1000"
    And I fill in "search_customer_firstname" with "titi"
    And I fill in "search_customer_lastname" with "tata"
    And I fill in "search_customer_phone" with "0123456789"
    And I press "search"
    Then I should be on "/staff/customer/123684973"
    And I should see "titi"
    And I should see "tata"
    And I should see "red"
    Then I click on "modify-card"
    And I wait "1000"
    Then I select "123584366" from "form_number"
    And I press "submit"
    Then I should be on "/staff/customer/123584366"


  @javascript
  Scenario: Using the offer of a customer
    And I am on "/staff/customer/123584366"
    And I should see "titi"
    And I should see "tata"
    And I should see "red"
    And I follow "useoffer-1"
    Then I should be on "/staff/customer/123584366"
    And I should see "The offer Welcome ! has been used."
