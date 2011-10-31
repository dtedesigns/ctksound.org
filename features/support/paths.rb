module NavigationHelpers
    def path_to(page_name)
        case page_name

        when /files page/
            "http://ctksound.dev/"
        when /homepage/
            root_path
        when /list of articles/
            articles_path
        when /mobile data entry page/

       # Add more page name => path mappings here

        else
            raise "Can't find mapping from \"#{page_name}\" to a path."
        end
    end
end

#World do |world|
    #world.extend NavigationHelpers
    #world
#end
