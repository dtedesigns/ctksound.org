require 'erb'

class SermonRecord
    def initialize(row)
        @row = row
    end

    def to_s
        erb :sermon_record, :locals => { :data => @row }, :layout => false
    end
end
