begin
    require 'deadweight'
rescue LoadError
end

desc "Run Deadweight CSS check"
task :deadweight do
    dw = Deadweight.new
    dw.root = "http://localhost:4000"
    dw.stylesheets = [ "/css/main.css" ]
    dw.pages = [ "/", "/2014/02/09" ]
    dw.ignore_selectors = /not-published|flash_notice|flash_error/
    puts dw.run
end

desc "Build"
task :build do
    sh "jekyll build"
end

desc "Serve"
task :serve do
    sh "jekyll serve -w"
end

desc "Deploy to AWS"
task :deploy => [:build] do
    sh "/Users/kevin/bin/s3cmd sync /Users/kevin/Sites/ctksound/_site/ s3://recordings.ctksound.org/"
end

task default: [deploy]
