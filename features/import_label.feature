Feature: import a label file
    In order to manage the creation of CUE files
    As a processor
    I want to import Audacity label files

    Scenario: dropped label file visibly uploads
        Given I am on the files page
        When a label file is dropped onto an available target
        Then I should see a progressbar
        And the file is removed from uploaded file list
        And the progressbar disappears

    Scenario: dropped file imports into database
        Given I am on the files page
        When a label file is dropped onto an available target
        Then the file importer verifies the file format
        And the file data is saved
        And success is returned to browser
