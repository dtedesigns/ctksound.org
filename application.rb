# routes.rb

require 'rubygems'
require 'sinatra'
require 'sinatra/sequel'
require 'logger'
require 'less'      # LESS CSS templates
require 'erb'
#require 'Sermon'
require 'yaml'

#require 'haml'
#require 'rdiscount' # for markdown
#require 'redcloth'  # for textile

#require 'partial_helper'
#set :textile, :layout_engine => :erb
#set :markdown, :layout_engine => :erb

#set :database, 'mysql://kgustavson:kgustavson11@localhost/ctksound'
#set :database, 'mysql://sermons:sermons@gandalf/sermons'
set :database, 'sqlite://db/ctksound.db'

class Sermons < Sequel::Model
    def validate
        super
        errors.add(:title, 'cannot be empty') if !title || title.empty?
    end
end

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


get '/' do
    dates = Hash.new #{ |l, k| l[k] = Hash.new(&l.default_proc) }
    teaching = database[:aces].order(:date)
    baptisms = database[:dedications].filter(:type => 'infant_baptism').order(:date)
    dedications = database[:dedications].filter(:type => 'dedication').order(:date)
    labels = database[:labels].order(:date)
    portraits = database[:portraits].exclude(:speaker => 'Test Speaker').order(:date)
    sermons = database[:sermons].filter(:type => 'Sermons').exclude(:title => 'Unavailable').order(:date.desc)

    sermons.each do |sermon|
        date = sermon[:date].to_s+'_0_sermon' #.strftime('%s_0_sermon')
        dates[date] = liquid(:sermon_record, :layout => false, :locals => sermon)
    end

    teaching.each do |lesson|
        date = lesson[:date].to_s+'_1_lesson' #.strftime('%s_1_lesson')
        dates[date] = liquid(:teaching_record, :layout => false, :locals => lesson)
    end

    portraits.each do |portrait|
        date = portrait[:date].to_s+'_2_portrait' #.strftime('%s_2_portrait')
        dates[date] = liquid(:portrait_record, :layout => false, :locals => portrait)
    end

    baptisms.each do |baptism|
        date = baptism[:date].to_s+'_3_baptism' #.strftime('%s_3_baptism')
        dates[date] = liquid(:infant_baptism_record, :layout => false, :locals => baptism)
    end

    dedications.each do |dedication|
        date = dedication[:date].to_s+'_4_dedication' #.strftime('%s_4_dedication')
        dates[date] = liquid(:dedication_record, :layout => false, :locals => dedication)
    end

    #erb :"wireframe", :locals => { :data => sermons }
    liquid :wireframe, :layout_engine => :erb, :locals => {
        :filters => erb(:filters, :layout => false),
        :items => dates,
        :keys => dates.keys.sort,
    }
end

# stylesheet route
get '/style/:name' do |n|
    less :"style/#{n}"
end


# upload with:
# curl -v -F "data=@/path/to/filename" http://localhost:4567/user/filename
post '/:user/:file' do 
    data = params[:data]
    userdir = File.join('/var/www/sound/webroot', 'recordings')
    filename = File.join(userdir, data[:file])
    FileUtils.mkdir_p(userdir)
    File.open(filename, 'wb') do |file|
        file.write(data[:tempfile].read)
    end
    mimetype = `file -Ib #{filename}`.gsub(/\n/,"")
    "wrote file of #{mimetype} to #{filename}\n"
    #"this is an invalid file type (#{mimetype}\n"
end

post '/upload' do
    # Destination
    upload_path = 'files/'
    upload = params["'upload'"]

    File.open(upload_path+upload[:filename], 'wb') do |file|
        file.write(upload[:tempfile].read)
    end
    params["'upload'"][:tempfile].path
end

put '/' do
    file = File.open('files/'+request.env['HTTP_X_FILENAME'], 'w+')
    request.body.read do |io|
        pos = io.tell
        while buffer = io.read(2**8)
            file.write(pos, buffer)
            pos = io.tell
        end
        file.close
    end

    media_type = request.media_type
    #YAML::dump(request.body.read)
end

#curl -v --location --upload-file file.txt http://localhost:4567/upload/
put '/upload/:id' do
  File.open(params[:id], 'w+') do |file|
    file.write(request.body.read)
  end
end

