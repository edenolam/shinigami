Feature: StaffGameSessionManagement

  Background:
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"


  @javascript
  Scenario: From panel to new game session

    Then I follow "new-gamesession"
    Then I should be on "/staff/gamesession"
    And I should see "Number of players"

  @javascript
  Scenario: From panel to new game session and making of a game session
    Then I follow "new-gamesession"
    Then I should be on "/staff/gamesession"
    Then I fill in "appbundle_gamesession_numberPlayers" with "4"
    And I wait "1000"
    Then I select "2" from "appbundle_gamesession_center"
    And I pick the date "06/12/2017"
    And I click on "appbundle_gamesession_time"
    And I wait "1000"
    And I press "OK"
    And I wait "1000"
    And I fill in "appbundle_gamesession_gameScores_0_playerName" with "Julien"
    And I fill in "appbundle_gamesession_gameScores_1_playerName" with "Fanny"
    And I fill in "appbundle_gamesession_gameScores_2_playerName" with "Lucky"
    And I fill in "appbundle_gamesession_gameScores_3_card" with "123584366"
    And I wait "1000"
    Then the "appbundle_gamesession_gameScores_3_playerName" field should contain "red"
    Then I fill in "appbundle_gamesession_gameScores_0_score" with "3"
    And I fill in "appbundle_gamesession_gameScores_1_score" with "15"
    And I fill in "appbundle_gamesession_gameScores_2_score" with "12"
    And I fill in "appbundle_gamesession_gameScores_3_score" with "6"
    And I press "submit"
    Then I should be on "/staff"
    And the ".alert-success" element should contain "The game session has been saved !"

  @javascript
  Scenario: Verify the gamesession has been saved in the customer view
    Then I follow "search"
    And I fill in "search_field" with "123584366"
    And I press "Search"
    Then I should be on "/staff/customer/123584366"
    And I click on "tab-gamesessions"
    And I wait "1000"
    And I should see "Game Session of the Wed 06 Dec 17"





