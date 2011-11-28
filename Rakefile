require 'rake/clean'
set error_format=
require 'rake/testtask'
require 'rdoc/task'
require 'fileutils'
require 'date'

# CI Reporter is only needed for the CI
begin
    #require 'ci/reporter/rake/rspec'     # use this if you're using RSpec
    #require 'ci/reporter/rake/cucumber'  # use this if you're using Cucumber
    require 'ci/reporter/rake/test_unit' # use this if you're using Test::Unit
rescue LoadError
end

task :default => 'ci:test'
task :spec => 'ci:test'

CLEAN.include "**/*.rbc"

namespace :ci do
    desc "Begin Testing"
    task :test => ['ci:clean','ci:specs','ci:unittest','ci:rcov','ci:docs']
    task :docs => ['ci:api']

    desc "Clean up the build directory"
    task :clean do
        rm "build/*"
    end

    namespace :specs do
        task :test do
            ENV['LANG'] = 'C'
            ENV.delete 'LC_CTYPE'
        end
    end

    namespace :unittest do
        task :test do
        end
    end

    namespace :api do
        desc 'Generate RDoc under build/api'
        task 'doc' => ['doc:api']
        task('doc:api') { sh "yardoc -o doc/api" }
        CLEAN.include 'doc/api'
   end

end

task(:tags) { sh "/home/kgustavson/bin/ctags-php.sh" }
task(:test) require
