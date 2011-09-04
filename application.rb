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
set :database, 'mysql://sermons:sermons@gandalf/sermons'

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
    aces = database[:aces].filter().order(:date)
    portraits = database[:portraits].filter().order(:date)
    sermons = database[:sermons].filter(:type => 'Sermons').order(:date.desc)

    sermons.each do |sermon|
        date = sermon[:date].to_s+'_0_sermon' #.strftime('%s_0_sermon')
        dates[date] = liquid(:sermon_record, :layout => false, :locals => sermon)
    end

    aces.each do |ace|
        date = ace[:date].to_s+'_1_ace' #.strftime('%s_0_ace')
        dates[date] = liquid(:ace_record, :layout => false, :locals => ace)
    end

    portraits.each do |portrait|
        date = portrait[:date].to_s+'_2_portrait' #.strftime('%s_0_portrait')
        dates[date] = liquid(:portrait_record, :layout => false, :locals => portrait)
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
                    #if(count($_FILES)>0) do
                        #$_FILES.each() do |file|
                            #data = params[:data]
                            #File.mv(upload_path+data['upload']['name'], data['upload']['tmp_name'])
                        #end
                    #else (isset($_GET['up']))
                    #// If the browser does not support sendAsBinary ()
                    #if(isset($_GET['base64'])) {
                        #$content = base64_decode(file_get_contents('php://input'));
                    #} else {
                        #$content = file_get_contents('php://input');
                    #}

                    #$headers = getallheaders();
                    #$headers = array_change_key_case($headers, CASE_UPPER);

                    #//if(file_put_contents($upload_folder.'/'.$headers['UP-FILENAME'], $content)) {
                    #//  echo 'done';
                    #//}
                    #exit();
                #}
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

