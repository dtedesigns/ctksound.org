#require 'rubygems'
#require 'sinatra'
require 'application'

set :run, false
set :environment, :development

#Sinatra::Application.default_options.merge!(
#  :run => false,
#  :env => ENV['RACK_ENV']
#)

FileUtils.mkdir_p 'log' unless File.exists?('log')
log = File.new("log/sinatra.log", "a")
$stdout.reopen(log)
$stderr.reopen(log)

run Sinatra::Application
