Feature: Security
  Scenario: RegisterForm
    Given I am on "/register"
    And I fill in "username" with "toto"
    And I fill in "email" with "toto@toto.com"
    And I fill in "password" with "test"
    And I fill in "lastname" with "tata"
    And I fill in "firstname" with "titi"
    And I fill in "nickname" with "nicki"
    And I fill in "adress" with "rue toto"
    And I fill in "phone" with "0123456"
    And I fill in "birthday" with "01/23/45"
    And I press "submit"
    Then I am on "/register_success"
    And I should see "Congratulation!!!"
@security
  Scenario: LoginForm
    Given I am on "/login"
    And I fill in "username" with "toto"
    And I fill in "password" with "test"
    And I press "submit"
    Then I should be logged as "username"


