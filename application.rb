require 'rubygems'
#require 'bundler/setup'
require 'sinatra'
require 'sinatra/config_file'
require 'sinatra/sequel'
require 'mysql'
require 'logger'
require 'erb'
require 'liquid'
require 'less'      # LESS CSS templates
require 'json'

set :liquid, :layout_engine => :erb
#require 'partial_helper'

#set :environment, :production
config_file 'config.yml'

require 'Models'

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

before do
    # FIXME this should happen when the connection is created
    database.loggers << Logger.new('log/ctksound_db.log')
end

# stylesheet route
get '/style/:name' do |n|
    less :"style/#{n}"
end

# Return json-formatted data object
get '/data/:record_type/:date' do
    database[ params[:record_type].to_sym ][:date => params[:date]].to_json
    #settings.records[ params[:record_type].to_sym ][:date => params[:date]].to_json
end

# Returns a formatted data record
get '/:record_type/:date' do
    record = database[ params[:record_type].to_sym ][:date => params[:date]]
    liquid(
        :"sermon_record",
        :layout => true,
        :locals => record
    )
end

# FIXME temporary info router action for development
get '/info' do
   <<-HTML
    <a href='/auth/twitter'>Sign in with Twitter</a>

    <form action='/auth/open_id' method='post'>
      <input type='text' name='identifier'/>
      <input type='submit' value='Sign in with OpenID'/>
    </form>
    HTML
end

# Get documentation
get '/docs' do
    "<p>Put documents here</p>"
end

# Get team info
get '/team' do
    "<p>Put team info here</p>"
end

# Index route
get '/' do
    cwd = Dir.pwd
    Dir.chdir('files')
    files = Dir['*'].entries
    Dir.chdir(cwd)

    dates = Hash.new #{ |l, k| l[k] = Hash.new(&l.default_proc) }

    Sermon.each do |sermon|
        date = sermon[:date].to_s+'_0_sermon' #.strftime('%s_0_sermon')
        sermon['type'] = 'sermon'
        dates[date] = liquid(:sermon_record, :layout => false, :locals => sermon.values)
    end

    Teaching.each do |lesson|
        date = lesson[:date].to_s+'_1_lesson' #.strftime('%s_1_lesson')
        dates[date] = liquid(:teaching_record, :layout => false, :locals => lesson.values)
    end

    Portrait.each do |portrait|
        date = portrait[:date].to_s+'_2_portrait' #.strftime('%s_2_portrait')
        dates[date] = liquid(:portrait_record, :layout => false, :locals => portrait.values)
    end

    Dedication.infant_baptism.each do |baptism|
        date = baptism[:date].to_s+'_3_baptism' #.strftime('%s_3_baptism')
        dates[date] = liquid(:infant_baptism_record, :layout => false, :locals => baptism.values)
    end

    Dedication.dedication.each do |baptism|
        date = dedication[:date].to_s+'_4_dedication' #.strftime('%s_4_dedication')
        dates[date] = liquid(:dedication_record, :layout => false, :locals => dedication.values)
    end

    @title = settings.title
    @subtitle = settings.subtitle

    liquid :items, :layout_engine => :erb, :locals => {
        :filters => liquid(:filters, :layout => false, :locals => { :files => files }),
        :items => dates,
        :keys => dates.keys.sort,
    }
end


# Import a file into the system
post '/import_file/*.*' do |file, ext|
    # Move using new filename (generated from record)
    # - If label file, import it
    #   + Need a function to import label files to Labels table
    #   + Q: Do I want to store the processed label files or not? (Yes, initially)
    # - If sound file, label it if possible.
    #   + Might want to refuse a drop until the database has been populated
    # Remove the filename from uploaded files list

    path = 'files/'+file+'.'+ext
    response = {}

    response[:id] = request.params['id']
    response[:type] = request.params['type']
    response[:file] = file
    response[:path] = path
    response[:filetype] = `/usr/bin/file -b "#{path}"`

    response.to_json
end

# Upload a file
put '/' do
    file = File.open('files/'+request.env['HTTP_X_FILENAME'], 'w+')
    file.write(request.body.read)
    file.close

    media_type = request.media_type
end


# The following routes should be A) Disabled and B) Deleted
# upload with:
#curl -v --location --upload-file file.txt http://localhost:4567/upload/
#put '/upload/:id' do
  #File.open(params[:id], 'w+') do |file|
    #file.write(request.body.read)
  #end
#end

# curl -v -F "data=@/path/to/filename" http://localhost:4567/user/filename
#post '/:user/:file' do 
    #data = params[:data]
    #userdir = File.join('/var/www/sound/webroot', 'recordings')
    #filename = File.join(userdir, data[:file])
    #FileUtils.mkdir_p(userdir)
    #File.open(filename, 'wb') do |file|
        #file.write(data[:tempfile].read)
    #end
    #mimetype = `file -Ib #{filename}`.gsub(/\n/,"")
    #"wrote file of #{mimetype} to #{filename}\n"
    #"this is an invalid file type (#{mimetype}\n"
#end

#post '/upload' do
    # Destination
    #upload_path = 'files/'
    #upload = params["'upload'"]

    #File.open(upload_path+upload[:filename], 'wb') do |file|
        #file.write(upload[:tempfile].read)
    #end
    #params["'upload'"][:tempfile].path
#end

# Demonstrate flash
#post '/set-flash' do
    #flash[:notice] = 'Thanks for signing up!'

    #puts flash[:notice]

    #flash.now[:notice] = 'Thanks for signing up!'
#end
