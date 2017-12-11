Feature: Security

  @javascript
  Scenario: register form of /register page
    Given I am on "/register"
    And I fill in "appbundle_account_customer_city" with "paris"
    And I pick the date "09/04/1997" in "datepicker"
    And I fill in "appbundle_account_customer_lastname" with "tata"
    And I fill in "appbundle_account_customer_firstname" with "titi"
    And I fill in "appbundle_account_customer_nickname" with "nicki"
    And I fill in "appbundle_account_customer_adress" with "rue toto"
    And I fill in "appbundle_account_customer_phone" with "0123456789"
    And I fill in "appbundle_account_username" with "toto"
    And I fill in "appbundle_account_email" with "toto@toto.com"
    And I fill in "appbundle_account_plainPassword_first" with "test"
    And I fill in "appbundle_account_plainPassword_second" with "test"
    And I scroll down
    And I click on "submit"
    Then I should be on "/login"
    And I should see "All right ! You've been registered !" in the ".alert-success" element


  @javascript
  Scenario: register form of the registration modal
    Given I am on "/"
    And I follow "Join us"
    And I fill in "appbundle_account_customer_lastname" with "titi"
    And I fill in "appbundle_account_customer_firstname" with "tata"
    And I fill in "appbundle_account_customer_nickname" with "tototo"
    And I fill in "appbundle_account_customer_adress" with "rue titi"
    And I fill in "appbundle_account_customer_phone" with "9876543210"
    And I fill in "appbundle_account_customer_city" with "paris"
    And I pick the date "25/10/2012" in "datepicker"
    And I fill in "appbundle_account_username" with "test"
    And I fill in "appbundle_account_email" with "test@toto.com"
    And I fill in "appbundle_account_plainPassword_first" with "toto"
    And I fill in "appbundle_account_plainPassword_second" with "toto"
    And I scroll down
    And I press "submit"
    Then I should be on "/login"
    And I should see "All right ! You've been registered !" in the ".alert-success" element

  @javascript
  Scenario: Login as a customer by the login form of /login page
    Given I am on "/login"
    And I fill in "username" with "toto"
    And I fill in "password" with "test"
    And I press "submit"
    Then I should be on "/customer"

  @javascript
  Scenario: Forgot password
    Given I am on "/login"
    And I should see "Forgot Password"
    And I follow "Forgot Password"
    Then I should be on "/forgot_password"
    And I fill in "emailField" with "test@toto.com"
    And I press "reset"
    And I should see "We've sent you an email with instructions to reset your password"

  @javascript
  Scenario: Login as a customer by the login form of the login modal
    Given I am on "/"
    And I follow "Sign in"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"

  @javascript
  Scenario: Creation of a staff member account
    Given I am on "/login"
    And I fill in "username" with "superstaff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"
    Then I am on "/super_staff"
    And I fill in "appbundle_account_username" with "staff2"
    And I fill in "appbundle_account_email" with "staff2@gmail.com"
    And I fill in "appbundle_account_plainPassword_first" with "staff"
    And I fill in "appbundle_account_plainPassword_second" with "staff"
    And I press "submit"
    Then I should be on "/login"
    And I should see "All right ! Now there's a new staff member !" in the ".alert-success" element






