#require 'rubygems'
#require 'sinatra'
require 'application'

# Authorization
require 'omniauth'
require 'omniauth-google-oauth2'
OpenSSL::SSL::VERIFY_PEER = OpenSSL::SSL::VERIFY_NONE

set :run, false
#set :environment, :development

use Rack::Session::Cookie, :secret => ENV['RACK_COOKIE_SECRET']

use OmniAuth::Builder do
    provider :google_oauth2, ENV['GOOGLE_KEY'], ENV['GOOGLE_SECRET'], {
        :scope => 'https://www.googleapis.com/auth/plus.me'
    }
end

FileUtils.mkdir_p 'log' unless File.exists?('log')
log = File.new("log/sinatra.log", "a")
$stdout.reopen(log)
$stderr.reopen(log)

run Sinatra::Application

# example simple rack app
#app = proc do |env|
    #[200, { "Content-Type" => "text/html" }, ["hello <b>world</b>"]]
#end
#run app
