#require 'rubygems'
#require 'sinatra'
require 'application'

set :run, false
#set :environment, :development

FileUtils.mkdir_p 'log' unless File.exists?('log')
log = File.new("log/sinatra.log", "a")
$stdout.reopen(log)
$stderr.reopen(log)

run Sinatra::Application
#app = proc do |env|
    #[200, { "Content-Type" => "text/html" }, ["hello <b>world</b>"]]
#end
#run app
