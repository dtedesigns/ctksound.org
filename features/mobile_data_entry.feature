Feature: enter data from phones
    In order to get accurate data into the system
    As a processor
    I want to enter sermon and teaching data from my phone

    Scenario: Enter mobile sermon data
        Given I am using a mobile browser
        And I am on the mobile data entry page
        And I have entered the values for a sermon
        When I submit the form
        Then I should see an in-progress message
        And the progressbar disappears
        And I should see a success message

    Scenario: Enter mobile teaching data
        Given I am using a mobile browser
        And I am on the mobile data entry page
        And I have entered the values for a lesson
        When I submit the form
        Then I should see an in-progress message
        And the progressbar disappears
        And I should see a success message
