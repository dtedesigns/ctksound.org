# routes.rb

require 'rubygems'
require 'sinatra'
require 'less'      # LESS CSS templates
#require 'haml'
require 'erb'
#require 'rdiscount' # for markdown
#require 'redcloth'  # for textile
#require 'partial_helper'
require 'liquid'
require 'fileutils'

#set :layout_engine => :erb
#set :textile, :layout_engine => :erb
#set :markdown, :layout_engine => :erb

# use Rack::Auth::Basic "Restricted Area" do |username, password|
#     [username, password] == ['admin','admin']
# end

def authorized?
    @auth ||= Rack::Auth::Basic::Request.new(request.env)
    @auth.provided? && @auth.basic? && @auth.credentials == ['admin','admin']
end

def protected!
    unless authorized?
        response['WWW-Authenticate'] = %(Basic realm="Restricted Area")
        throw(:halt, [401, "Not authorized\n"])
    end
end

# Examples
#post '/' do
     #create something
#end


#put '/' do
    # replace something
#end

#patch '/' do
    # modify something
#end

delete '/' do
    # annihilate something
end

options '/' do
    # appease something
end


get '/hello/:name' do |n|
    "Hello #{n}!"
end

get '/haml' do
    haml :example
end

get '/erb' do
    erb :example
end

get '/markdown' do
    markdown :example
end

get '/textile' do
    textile :example
end

# Fox Valley Theological Society
get '/' do
    liquid :index, :locals => { :sidebar => "sidebary", :event => "eventy" }
end

get '/who_are_we' do
    textile :who_are_we
end

get '/events' do
    erb :events, :locals => { :test => "fubar" }
end

get '/talk_to_me' do
    textile :talk_to_me
    #@full = markdown :talk_to_me
end

# Possible stylesheet route
get '/style/:name' do |n|
    less :"style/#{n}"
end

get '/default.css' do
    #"put template here"
    less :"style/default"
end

get '/test' do
    protected!
    str = ''
    Dir.foreach('views/event') { |f|
        next if f.index('.') == 0
        str << "#{f}<br>"
    }
    str
end

get '/upload/:name/:filename' do
    erb :form
end

#post '/upload/:name/:filename' do
    #userdir = File.join("files" , params[:name])
    #FileUtils.mkdir_p(userdir)
    #filename = File.join(userdir, params[:filename])
    #datafile = params[:data]
    #"#{datafile[:tempfile].inspect}\n"
#
    #File.copy(datafile[:tempfile], filename)
    #File.open(filename, 'wb') do |file|
    # file.write(datafile[:tempfile].read)
    #end
    #"wrote to #{filename}\n"
#end

# upload with:
# curl -v -F "data=@/path/to/filename" http://localhost:4567/user/filename

post '/:type/:date' do
    if ( ['recordings','labels','Originals'].include? params[:type] )
        userdir = File.join("/var/www/sound/webroot", params[:type])
        FileUtils.mkdir_p(userdir)
        filename = File.join(userdir, params[:date])
        datafile = params[:data]
        File.open(filename, 'wb') do |file|
            file.write(datafile[:tempfile].read)
        end
        "wrote to #{filename}\n"
    else
        "this is an invalid file type\n"
    end
end

get '/dnduploader' do
    erb :index, :layout => false
end

put '/' do
    "uploaded #{env['HTTP_X_FILENAME']} - #{request.body.read.size} bytes"
end

#use_in_file_templates!

__END__

@@ form
<form action="" method="post" enctype="multipart/form-data">
<p>
<label for="file">File:</label>
<input type="file" name="file">
</p>

<p>
<input name="commit" type="submit" value="Upload" />
</p>
</form>
