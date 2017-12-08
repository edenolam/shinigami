Feature: StaffNewsletterManagement

  Background:
    Given I am on "/login"
    And I fill in "username" with "staff"
    And I fill in "password" with "staff"
    And I press "submit"
    Then I should be on "/staff"


  @javascript
  Scenario: From panel to offers CRUD
    Then I follow "newsletters"
    Then I should be on "/staff/newsletter/list"
    And I should see "Create newsletter"

  @javascript
  Scenario: From offers list to newsletter creation, creation of an newsletter
    Then I follow "newsletters"
    Then I should be on "/staff/newsletter/list"
    And I should see "Create newsletter"
    And I follow "new-newsletter"
    Then I should be on "/staff/newsletter/create"
    And I fill in "appbundle_newsletter_name" with "new Shinigami Laser Bourg-Palette"
    And I fill in "appbundle_newsletter_title" with "A new Shinigami Laser in Bourg-Palette !"
    And I fill in "appbundle_newsletter_content" with "A new Shinigami Laser in Bourg-Palette !"
    And I select "red" from "appbundle_newsletter_theme"
    And I press "submit"
    Then I should be on "/staff/newsletter/list"
    And I should see "the newsletter new Shinigami Laser Bourg-Palette has been saved"

  @javascript
  Scenario: Modify an newsletter
    Then I follow "newsletters"
    Then I should be on "/staff/newsletter/list"
    And I follow "modify-1"
    Then I should be on "/staff/newsletter/modify/1"
    And I fill in "appbundle_newsletter_name" with "new Shinigami Laser Safrania"
    And I fill in "appbundle_newsletter_title" with "A new Shinigami Laser in Safrania !"
    And I fill in "appbundle_newsletter_content" with "A new Shinigami Laser in Safrania !"
    And I select "green" from "appbundle_newsletter_theme"
    And I press "submit"
    Then I should be on "/staff/newsletter/list"
    And I should see "the newsletter new Shinigami Laser Safrania has been saved"


  @javascript
  Scenario: Send a newsletter to customers
    Then I follow "newsletters"
    Then I should be on "/staff/newsletter/list"
    And I follow "send-1"
    Then I should see "The email has been sent !"

  @javascript
  Scenario: Delete a newsletter
    Then I follow "newsletters"
    Then I should be on "/staff/newsletter/list"
    And I follow "delete-1"
    And I wait "1000"
    Then I should not see "new Shinigami Laser Safrania"
