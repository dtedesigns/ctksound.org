require 'rubygems'
require 'sequel'

#MYSQL = Sequel.connect('mysql://kgustavson:kgustavson11@localhost/ctksound')
MYSQL = Sequel.connect('mysql://sermons:sermons@gandalf/sermons')
SQLITE = Sequel.sqlite('db/ctksound.db')

aces = MYSQL[:aces]
aces1 = SQLITE[:aces]
aces1.truncate
aces1.insert_multiple(aces)
puts "Aces: #{aces.count}, #{aces1.count}"

sermons = MYSQL[:sermons]
sermons1 = SQLITE[:sermons]
sermons1.truncate
sermons1.insert_multiple(sermons)
puts "sermons: #{sermons.count}, #{sermons1.count}"

portraits = MYSQL[:portraits]
portraits1 = SQLITE[:portraits]
portraits1.truncate
portraits1.insert_multiple(portraits)
puts "portraits: #{portraits.count}, #{portraits1.count}"

dedications = MYSQL[:dedications]
dedications1 = SQLITE[:dedications]
dedications1.truncate
dedications1.insert_multiple(dedications)
puts "dedications: #{dedications.count}, #{dedications1.count}"

