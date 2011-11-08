# routes.rb

require 'rubygems'
require 'sinatra'
require 'sinatra/sequel'
require 'logger'
require 'erb'
require 'liquid'
require 'less'      # LESS CSS templates
require 'json'
require 'yaml'

#require 'haml'
#require 'rdiscount' # for markdown
#require 'redcloth'  # for textile

#require 'partial_helper'
#set :textile, :layout_engine => :erb
#set :markdown, :layout_engine => :erb

#enable :sessions

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

#set :database, 'mysql://kgustavson:kgustavson11@localhost/ctksound'
#set :database, 'mysql://sermons:sermons@gandalf/sermons'
set :database, 'sqlite://db/ctksound.db'
#set :database, Sequel.connect('sqlite://db/ctksound.db')
require 'Models'
set :records, {
    :teaching   => database[:aces].order(:date),
    :baptism    => database[:dedications].filter(:type => 'infant_baptism').order(:date),
    :dedication => database[:dedications].filter(:type => 'dedication').order(:date),
    :label      => database[:labels].order(:date),
    :portrait   => database[:portraits].exclude(:speaker => 'Test Speaker').order(:date),
    :sermon     => database[:sermons].filter(:type => 'Sermons').exclude(:title => 'Unavailable').order(:date.desc)
}

before do
    # FIXME this should happen when the connection is created
    database.loggers << Logger.new('log/ctksound_db.log')
end

# stylesheet route
get '/style/:name' do |n|
    less :"style/#{n}"
end

get '/data/:record_type/:date' do
    options.records[ params[:record_type].to_sym ][:date => params[:date]].to_json
end

get '/:record_type/:date' do
    record = options.records[ params[:record_type].to_sym ][:date => params[:date]]
    liquid(
        :"#{params['record_type']}_record",
        :layout => false,
        :locals => record
    )
end

get '/docs' do
    "<p>Put documents here"
end

get '/team' do
    "<p>Put team info here</p>"
end

get '/' do
    cwd = Dir.pwd
    Dir.chdir('files')
    files = Dir['*'].entries
    Dir.chdir(cwd)

    dates = Hash.new #{ |l, k| l[k] = Hash.new(&l.default_proc) }
    records = options.records

    records[:sermon].each do |sermon|
        date = sermon[:date].to_s+'_0_sermon' #.strftime('%s_0_sermon')
        sermon['type'] = 'sermon'
        dates[date] = liquid(:sermon_record, :layout => false, :locals => sermon)
    end

    records[:teaching].each do |lesson|
        date = lesson[:date].to_s+'_1_lesson' #.strftime('%s_1_lesson')
        dates[date] = liquid(:teaching_record, :layout => false, :locals => lesson)
    end

    records[:portrait].each do |portrait|
        date = portrait[:date].to_s+'_2_portrait' #.strftime('%s_2_portrait')
        dates[date] = liquid(:portrait_record, :layout => false, :locals => portrait)
    end

    records[:baptism].each do |baptism|
        date = baptism[:date].to_s+'_3_baptism' #.strftime('%s_3_baptism')
        dates[date] = liquid(:infant_baptism_record, :layout => false, :locals => baptism)
    end

    records[:dedication].each do |dedication|
        date = dedication[:date].to_s+'_4_dedication' #.strftime('%s_4_dedication')
        dates[date] = liquid(:dedication_record, :layout => false, :locals => dedication)
    end

    liquid :items, :layout_engine => :erb, :locals => {
        :filters => liquid(:filters, :layout => false, :locals => { :files => files }),
        :items => dates,
        :keys => dates.keys.sort,
    }
end


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

put '/' do
    file = File.open('files/'+request.env['HTTP_X_FILENAME'], 'w+')
    file.write(request.body.read)
    file.close

    media_type = request.media_type
end

#
# The following routes should be A) Disabled and B) Deleted
#

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
