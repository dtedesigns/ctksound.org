# CTKSound.org Model classes
# Author:: Kevin Gustavson
# Copyright:: Copyright (c)2011 Kevin D. Gustavson. All rights reserved.
# License::   On request only.

# This is the Portrait model (portraits table)
class Portrait < Sequel::Model(:portraits)
  plugin :validation_helpers
  subset(:published) {:published != nil}
  subset(:unpublished) {:published == nil}
  subset(:cleanup) {:speaker != 'Test Speaker'}

  def validate
    super
    validates_presence [
      :series,
      :speaker
    ]

    validates_unique :date
  end
end

