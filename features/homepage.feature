Feature: Homepage
<<<<<<< HEAD
=======
  Given I am on homepage
  Then I should see “Welcome to Shinigami Laser”
  And I should see “Register”
  And I should see “Login”

>>>>>>> a5bfac4a50d2b37ff81ce7b8a1928d8dd3941eef

  Scenario: See the home page
    Given I am on "/"
    Then I should see "Welcome to Shinigami Laser"
    Then I should see "register" in the "nav" element
    Then I should see "login" in the "nav" element
    And the response status code should be 200

  Scenario: From home page to register page
    Given I am on "/"
    And I follow "register"
    Then I should be on "/register"
    And the response status code should be 200
    And I should see "Registration"

  Scenario: From homepage to login page
    Given I am on "/"
    And I follow "login"
    Then I should be on "/login"
    And the response status code should be 200
    And  I should see "Login"