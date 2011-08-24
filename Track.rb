class Track
    attr_reader :start, :stop, :title, :person
    attr_writer :start, :stop, :title, :person

    def length
        @stop - @start
    end

    def initialize(start, stop, title, person)
        @start = start
        @stop = stop
        @title = title
        @person = person
    end

    def to_s
        "#{@start}\t#{@stop}\t#{@title}"
    end
