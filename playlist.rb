require 'Track'

class Playlist
    # this is a getter
    def playlist
        @playlist
    end
    # this also defines getters
    attr_reader :stop, :title, :person

    # this is a setter
    def start=(newStart)
        @start = newStart
    end

    # these are setters
    attr_writer :stop, :title, :person

    # this is a constructor
    def initialize
        @playlist = Array.new
    end

    # this is a method
    def addTrack(start,stop,title,person,sequence)
        @playlist[] = {
            sequence => Track.new(start,stop,title,person)
        }
    end

    # override the default to_s method
    def to_s
        @playlist.each { |track|
            track
        }
    end

    def import(file)
        f = File.open(file)
        f.each do |track|
            print line
        end
        f.close
    end

end
