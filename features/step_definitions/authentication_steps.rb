Given /I am on the home page/ do
  visit "http://www.codeigniter.com/"
end

Given /I am logged out/ do
  click_link 'Logout' if not contain('<a href="http://codeigniter.com/forums/member/login/">Login</a>')
end

When /I click on (.*)/ do |link|
  click_link link
end

Then /I should see the login page/ do
  response_body.should have_xpath(%\//input[@name='username']\)
  response_body.should have_xpath(%\//input[@name='password']\)
  response_body.should contain("Login Required")
end

Given /I am on the login page/ do
  visit "http://symfony/login/"
end

When /I fill in the (.*) input with (.*)/ do |input, value|
  fill_in input, :with => value
end

When /^I click the button (.*)$/ do |button|
  click_button button
end

Then /^I should see the logged in page$/ do
  response_body.should contain("You are now logged in")
end

Given /I am logged in/ do
  visit "http://symfony"

  if contain('<a href="http://codeigniter.com/forums/member/login/">Login</a>')
    click_link "Login"
    fill_in 'username', :with => "**username**"
    fill_in 'password', :with => "**password**"
    click_button "Submit"
    response_body.should contain("You are now logged in")
  end
end

Then /I should see the logged out page/ do
  response_body.should contain("You are now logged out")
end

When /^I click Login$/ do
      pending # express the regexp above with the code you wish you had
end

When /^I fill in the password with \*\*password\*\*$/ do
      pending # express the regexp above with the code you wish you had
end

