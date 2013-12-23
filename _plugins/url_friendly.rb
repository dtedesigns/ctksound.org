module Jekyll
  module AssetFilter
    def scripture_url(input)
      temp = input.gsub(':', '_')
      return temp
    end

    def title_url(input)
      temp = input.gsub('&', 'and')
      temp.gsub!(/[\/]/, '_')
      return temp.gsub(/[^a-zA-Z0-9 \-\/,_]/, '')
    end

    def format_time(time)
      minutes = (time / 60).to_i
      seconds = (time % 60).to_i
      return sprintf('%d:%02d', minutes, seconds)
    end
  end
end

Liquid::Template.register_filter(Jekyll::AssetFilter)
