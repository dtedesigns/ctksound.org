require 'application'
#require 'capybara'
require 'capybara/dsl'
require 'test/unit'

ENV['RACK_ENV'] = 'test'

class IndexTest < Test::Unit::TestCase
    include Capybara
    Capybara.default_driver = :selenium

    def setup
        Capybara.app = Sinatra::Application.new
    end

    def test_header
        visit '/'
        assert page.has_content?('CTKSound.org')
    end

end
