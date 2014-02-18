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
