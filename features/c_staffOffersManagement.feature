Feature: StaffOffersManagement

  Background:
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"

  @javascript
  Scenario: From login to staff panel
    And I should see "Newsletters" in the "nav" element
    And I should see "Offers" in the "nav" element
    And I should see "Search by card"

  @javascript
  Scenario: From panel to offers CRUD
    Then I follow "offers"
    Then I should be on "/staff/offers/list"
    And I should see "Create new offer"

  @javascript
  Scenario: From offers list to offers creation, creation of an offer
    Then I follow "offers"
    Then I should be on "/staff/offers/list"
    And I should see "Create new offer"
    And I follow "new-offer"
    Then I should be on "/staff/offers/new"
    And I fill in "appbundle_offer_code" with "SCR100"
    And I fill in "appbundle_offer_count" with "100"
    And I fill in "appbundle_offer_name" with "Beginner"
    And I select "score" from "appbundle_offer_offerType"
    And I fill in "appbundle_offer_description" with "Yey ! Welcome in the family of Shinigami Laser !"
    And I fill in "appbundle_offer_level" with "1"
    And I press "submit"
    Then I should be on "/staff/offers/list"
    And I should see "The offer Beginner has been saved"

  @javascript
  Scenario: Modify an offer
    Then I follow "offers"
    Then I should be on "/staff/offers/list"
    And I follow "modify-1"
    Then I should be on "/staff/offers/modify/1"
    And I fill in "appbundle_offer_code" with "WELCM"
    And I fill in "appbundle_offer_count" with "0"
    And I fill in "appbundle_offer_name" with "Welcome !"
    And I select "welcome" from "appbundle_offer_offerType"
    And I fill in "appbundle_offer_description" with "Baby shinigami"
    And I fill in "appbundle_offer_level" with "1"
    And I press "submit"
    Then I should be on "/staff/offers/list"
    And I should see "Welcome !"

  @javascript
  Scenario: Disable an offer and reactivate it
    Then I follow "offers"
    Then I click on "active-1"
    Then I wait "1000"
    Then I should see "close" in the "#active-1" element
    Then I click on "active-1"
    Then I wait "1000"
    Then I should see "check" in the "#active-1" element