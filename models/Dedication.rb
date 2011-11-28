# CTKSound.org Model classes
# Author:: Kevin Gustavson
# Copyright:: Copyright (c)2011 Kevin D. Gustavson. All rights reserved.
# License::   On request only.

# This is the Dedication model (dedications table)
class Dedication < Sequel::Model(:dedications)
  plugin :validation_helpers
  # The published dedication filter
  subset(:published) {:published != nil}
  # The unpublished dedication filter
  subset(:unpublished) {:published == nil}
  # The dedication-only filter
  subset(:dedication) {:type == 'dedication'}
  # The baptism-only filter
  subset(:infant_baptism) {:type == 'infant_baptism'}

  def validate
    super
    validates_presence [
      :official,
      :child,
      :type
    ]

    validates_unique :date
  end
end

