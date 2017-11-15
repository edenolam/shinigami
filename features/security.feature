Feature: Security

  @security
  Scenario: RegisterForm
    Given I am on "/register"
    And I fill in "appbundle_account_username" with "toto"
    And I fill in "appbundle_account_email" with "toto@toto.com"
    And I fill in "appbundle_account_plainPassword_first" with "test"
    And I fill in "appbundle_account_plainPassword_second" with "test"
    And I fill in "appbundle_account_customer_lastname" with "tata"
    And I fill in "appbundle_account_customer_firstname" with "titi"
    And I fill in "appbundle_account_customer_nickname" with "nicki"
    And I fill in "appbundle_account_customer_adress" with "rue toto"
    And I fill in "appbundle_account_customer_phone" with "0123456"
    And I select "01" from "appbundle_account_customer_birthday_day"
    And I select "11" from "appbundle_account_customer_birthday_month"
    And I select "2017" from "appbundle_account_customer_birthday_year"
    And I press "submit"
    Then I should be on "/"
    And the response status code should be 200
    And I should see "All right ! You've been registered !" in the ".alert-success" element

  @security
  Scenario: LoginForm
    Given I am on "/login"
    And I fill in "username" with "toto"
    And I fill in "password" with "test"
    And I press "submit"
    Then I should be on "/"
    And I should see "toto" in the ".app-user-username" element



