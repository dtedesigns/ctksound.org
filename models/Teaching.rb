# CTKSound.org Model classes
# Author:: Kevin Gustavson
# Copyright:: Copyright (c)2011 Kevin D. Gustavson. All rights reserved.
# License::   On request only.

# This is the Teaching model (aces table)
class Teaching < Sequel::Model(:aces)
  plugin :validation_helpers
  # The published teaching filter
  subset(:published) {:published != nil}
  # The unpublished teaching filter
  subset(:unpublished) {:published == nil}

  def validate
    super
    validates_presence [
      :title,
      :series,
      :teacher,
      :disk
    ]

    validates_unique :date
  end
end

