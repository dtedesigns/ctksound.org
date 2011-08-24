require 'SermonRecord'
require 'Record'
require 'sequel'
require 'logger'

DB = Sequel.connect('mysql://kgustavson:kgustavson11@localhost/ctksound', :logger => Logger.new('log/ctksound_db.log'))
class Sermon < Record

    def fetchData(filters)
        rows = []
        sermons = DB['select * from sermons'].limit(10).order(:date.desc)

        sermons.each() do |sermon|
            rows.push(SermonRecord.new(sermon))
        end

        return rows
    end
end
