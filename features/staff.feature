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
  Scenario: Search customer by card number
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"
    And the response status code should be 200
    And I fill in "search_field" with "234567899"
    And I press "Search"
    Then I should be on "/staff/card/234567899"
    And the response status code should be 200
    And the ".firstname" element should contain "michel"
    And the ".lastname" element should contain "varuk"
    And the ".nickname" element should contain "mimi"

  @staff
  Scenario: Search customer by name
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"
    And the response status code should be 200
    And I fill in "search_customer_firstname" with "michel"
    And I fill in "search_customer_lastname" with "varuk"
    And I fill in "search_customer_phone" with "0664659887"
    And I press "Search"
    Then I should be on "/staff/card/234567899"
    And the response status code should be 200
    And the ".nickname" element should contain "mimi"
    And the ".firstname" element should contain "michel"
    And the ".lastname" element should contain "varuk"

  @staff
  Scenario: edit profile customer
    Given I am on "/staff/card/234567899"
    And I press "#modify-profile"
    Then I should be on "/staff/card/234567899/editcustomer"
    And the response status code should be 200

  @staff
  Scenario: edit profile customer
    Given I am on "/staff/card/234567899"
    And I press "#modify-profile"
    Then I should be on "/staff/card/234567899/editcustomer"
    And the response status code should be 200




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
  Scenario: From panel to new game session and making of a game session
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


  @staff
  Scenario: From panel to offers CRUD
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"
    And the response status code should be 200
    Then I follow "offers"
    Then I should be on "/staff/offers/list"
    And the response status code should be 200
    And I should see "Create new offer"


  @staff
  Scenario: From offers list to offers creation, creation of an offer
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"
    And the response status code should be 200
    Then I follow "offers"
    Then I should be on "/staff/offers/list"
    And the response status code should be 200
    And I should see "Create new offer"
    And I follow "new-offer"
    Then I should be on "/staff/offers/new"
    And the response status code should be 200
    And I fill in "appbundle_offer_code" with "SCR100"
    And I fill in "appbundle_offer_count" with "100"
    And I fill in "appbundle_offer_name" with "Beginner"
    And I fill in "appbundle_offer_offerType" with "score"
    And I fill in "appbundle_offer_description" with "Yey ! Welcome in the family of Shinigami Laser !"
    And I fill in "appbundle_offer_level" with "1"
    And I press "submit"
    Then I should be on "/staff/offers/list"
    And the response status code should be 200
    And I should see "The offer Beginner has been saved"