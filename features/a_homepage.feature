Feature: Homepage

  Background:
    Given I am on "/"

  @javascript
  Scenario: See the home page
    Then I should see "Welcome to Shinigami Laser"
    Then I should see "register" in the "nav" element
    Then I should see "login" in the "nav" element
  @javascript
  Scenario: Open register modal with the register nav link
    Given I follow "register"
    Then I should see the "#modal-registration" modal
  @javascript
  Scenario: Open register modal with the login nav link
    Given I follow "login"
    Then I should see the "#modal-login" modal
  @javascript
  Scenario: Open register modal with the join us button
    Given I follow "Join us"
    Then I should see the "#modal-registration" modal
  @javascript
  Scenario: Open register modal with the sign in button
    Given I follow "Sign in"
    Then I should see the "#modal-login" modal