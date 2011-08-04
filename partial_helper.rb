# Render the page once.
# Usage: partial :foo
#
# foo will be rendered once for each element in the array, passing in a local variable named "foo"
# Usage: partial :foo, :collection => #my_foos

helpers do
    def partial(template, *args)
        options = args.extract_options!
        options.merge!(:layout => false)
        if collection = option.delete(:collection) then
            collection.inject([]) do |buffer, member|
                buffer << haml(template, options.merge(
                    :layout => false,
                    :locals => {template.to_sym => member}
                    )
                )
            end.join("\n")
        else
            haml(template, options)
        end
    end
end
