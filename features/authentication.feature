Feature: Authentication
  In order to use the CodeIgniter site
  As a user
  I want to be able to login and logout

  Scenario: Login
    Given I am on the home page
    And I am logged out
    When I click Login
    Then I should see the login page

  Scenario: Login processing
    Given I am on the login page
    When I fill in the username input with **login**
    And I fill in the password with **password**
    And I click the button Submit
    Then I should see the logged in page

  Scenario: Logout
    Given I am logged in
    And I am on the home page
    When I click on Logout
    Then I should see the logged out page

