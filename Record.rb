class Record

    def initialize(filters)
        @records = fetchData(filters)
    end

    def to_s
        @records.each() do |record|
            output += record.to_s
        end
    end

end
