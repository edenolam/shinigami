Feature: Staff
  @staff
  Scenario: From login to staff panel
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"
    And the response status code should be 200
    And I should see "Newsletter" in the "nav" element
    And I should see "Offers" in the "nav" element
    And I should see "Search by card"

  @staff
  Scenario: Search customer by card
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"
    And the response status code should be 200
    And I fill in "search_field" with "123456"
    And I press "Search"
    Then I should be on "/staff/card/123456"
    And the response status code should be 200
    And the ".firstname" element should contain "julien"
    And the ".lastname" element should contain "basquin"
    And the ".nickname" element should contain "staff"

  @staff
  Scenario: From panel to new game session
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"
    And the response status code should be 200
    Then I follow "new-gamesession"
    Then I should be on "/staff/gamesession"
    And the response status code should be 200
    And I should see "Number of players"

  @staff
  Scenario: From panel to new game session
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"
    And the response status code should be 200
    Then I follow "new-gamesession"
    Then I should be on "/staff/gamesession"
    And the response status code should be 200
    Then I fill in "appbundle_gamesession_numberPlayers" with "4"
    And I select "12, rirjie" from "appbundle_gamesession_center"
    And I fill in "appbundle_gamesession_date" with "17 December,2017"
    And I fill in "appbundle_gamesession_gameScores_0_playerName" with "Julien"
    And I fill in "appbundle_gamesession_gameScores_1_playerName" with "Fanny"
    And I fill in "appbundle_gamesession_gameScores_2_playerName" with "Lucky"
    And I fill in "appbundle_gamesession_gameScores_3_playerName" with "Mimi"
    And I fill in "card-1" with "1234567890"
    And I fill in "card-2" with "2345678901"
    And I fill in "score-1" with "3"
    And I fill in "score-2" with "15"
    And I fill in "score-3" with "12"
    And I fill in "score-4" with "6"
    And I press "submit"
    Then I should be on "/staff/gamesession"
    And the response status code should be 200
    And the ".alert-success" element should contain "The game session has been saved."
