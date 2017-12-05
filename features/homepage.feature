Feature: Homepage

  Background:
    Given I am on "/"

  @homepage
  Scenario: See the home page
    Then I should see "Welcome to Shinigami Laser"
    Then I should see "register" in the "nav" element
    Then I should see "login" in the "nav" element
  @homepage
  Scenario: Open register modal with the register nav link
    Given I follow "register"
    Then I should see the "#modal-registration" modal
  @homepage
  Scenario: Open register modal with the login nav link
    Given I follow "login"
    Then I should see the "#modal-login" modal
  @homepage
  Scenario: Open register modal with the join us button
    Given I follow "Join us"
    Then I should see the "#modal-registration" modal
  @homepage
  Scenario: Open register modal with the sign in button
    Given I follow "Sign in"
    Then I should see the "#modal-login" modal