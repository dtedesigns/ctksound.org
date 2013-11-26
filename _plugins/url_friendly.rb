module Jekyll
  module AssetFilter
    def scripture_url(input)
      return input.gsub(/[:]/, '_')
    end

    def title_url(input)
      input.gsub!(/[\/]/, '_')
      return input.gsub(/[^a-zA-Z0-9_-\/]/, '')
    end
  end
end

Liquid::Template.register_filter(Jekyll::AssetFilter)
