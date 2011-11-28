# CTKSound.org Model classes
# Author:: Kevin Gustavson
# Copyright:: Copyright (c)2011 Kevin D. Gustavson. All rights reserved.
# License::   On request only.

# This is the Label model (labels table)
class Label < Sequel::Model(:labels)
  plugin :validation_helpers

  def validate
    super
    validates_presence [
      :sermon_id,
      :index,
      :title,
      :start,
      :end
    ]
  end
end

