# routes.rb

require 'rubygems'
require 'sinatra'
require 'sinatra/sequel'
require 'logger'
require 'less'      # LESS CSS templates
require 'erb'
require 'Sermon'

#require 'haml'
#require 'rdiscount' # for markdown
#require 'redcloth'  # for textile

#require 'fileutils'
#require 'ftools'

#require 'partial_helper'
#set :textile, :layout_engine => :erb
#set :markdown, :layout_engine => :erb

set :database, 'mysql://kgustavson:kgustavson11@localhost/ctksound'

class Sermons < Sequel::Model
    def validate
        super
        errors.add(:title, 'cannot be empty') if !title || title.empty?
    end
end
#Sermons.row_proc = proc{|row| erb :sermon_record, :locals => { :data => row }, :layout => false }

# use Rack::Auth::Basic "Restricted Area" do |username, password|
#     [username, password] == ['admin','admin']
# end

#def authorized?
    #@auth ||= Rack::Auth::Basic::Request.new(request.env)
    #@auth.provided? && @auth.basic? && @auth.credentials == ['admin','admin']
#end

#def protected!
    #unless authorized?
        #response['WWW-Authenticate'] = %(Basic realm="Restricted Area")
        #throw(:halt, [401, "Not authorized\n"])
    #end
#end
#DB = Sequel.connect('mysql://kgustavson:kgustavson11@localhost/ctksound', :logger => Logger.new('log/ctksound_db.log'))

def getData
    rows = []

    sermons = DB['select * from sermons'].limit(10).order(:date.desc)
    sermons.each() do |sermon|
        rows.push(erb :sermon_record, :locals => { :data => sermon }, :layout => false)
    end

    #portraits = DB['select * from portraits'].limit(10)
    #aces = DB['select * from aces'].limit(10)
    #dedications = DB['select * from dedications'].limit(10)

    rows
end


get '/' do
    sermons = database[:sermons].filter('date > 2011-01-01').order(:date.desc)
    erb :"wireframe", :locals => { :data => sermons }
end

# Possible stylesheet route
get '/style/:name' do |n|
    less :"style/#{n}"
end

get '/default.css' do
    #"put template here"
    less :"style/default"
end

get '/sequel' do
    rows = []

    DB = Sequel.connect('mysql://kgustavson:kgustavson11@localhost/ctksound', :logger => Logger.new('log/ctksound_db.log'))

    portraits = DB['select date,speaker,comment,created_at,updated_at from portraits']
    portraits = portraits.filter("comment like '%test%'")
    puts "filtered: #{portraits.count}"
    portraits.order(:date.desc).each do |row|
        rows += [row.to_s]
    end

    rows.join('<br>')

    #sermons = DB[:sermons]
    #p sermons.count
    #p sermons.first

    #DB['desc portraits'].each do |col|
        #p col
    #end
end

get '/test' do
    #protected!
    str = ''
    Dir.foreach('views/event') { |f|
        next if f.index('.') == 0
        str << "#{f}<br>"
    }
    str
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

post '/something' do 
    #if ( ['recordings','labels','Originals'].include? params[:type] )
        datafile = params[:data]
        userdir = File.join('/var/www/sound/webroot', 'recordings')
        filename = File.join(userdir, '2011-07-31')
        FileUtils.mkdir_p(userdir)
        File.open(filename, 'wb') do |file|
            file.write(datafile[:tempfile].read)
        end
        mimetype = `file -Ib #{filename}`.gsub(/\n/,"")
        "wrote file of #{mimetype} to #{filename}\n"
    #else
        #"this is an invalid file type (#{mimetype}\n"
    #end
end

get '/dndupload' do
    erb :index
end

put '/something' do
    datafile = params[:data]
    #userdir = File.join('/var/www/sound/webroot', 'recordings')
    #FileUtils.mkdir_p(userdir)
    filename = "/var/www/sound/webroot/recordings/2011-07-31"
    #FileUtils.cp(datafile[:tempfile], filename)
    #File.open(filename, 'wb') do |file|
        #file.write(datafile[:tempfile].read)
    #end
    #mimetype = `file -Ib #{filename}`.gsub(/\n/,"")
    #"wrote file of #{mimetype} to #{filename}\n"
    #"uploaded #{env['HTTP_X_FILENAME']} - #{request.body.read.size} bytes"
end


put '/' do
  tempfile = params['file'][:tempfile]
  filename = params['file'][:filename]
  File.mv(tempfile.path,File.join(File.expand_path(File.dirname(File.dirname(__FILE__))),"public","#{filename}"))
  #redirect '/'
end

#curl -v --location --upload-file file.txt http://localhost:4567/upload/
put '/upload/:id' do
  File.open(params[:id], 'w+') do |file|
    file.write(request.body.read)
  end
end

