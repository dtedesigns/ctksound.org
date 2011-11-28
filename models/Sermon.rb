# CTKSound.org Model classes
# Author:: Kevin Gustavson
# Copyright:: Copyright (c)2011 Kevin D. Gustavson. All rights reserved.
# License::   On request only.

# This is the Sermon model (sermons table)
class Sermon < Sequel::Model(:sermons)
  plugin :validation_helpers
  # The published sermons filter
  subset(:published) {:published != nil}
  # The unpublished sermons filter
  subset(:unpublished) {:published == nil}
  subset(:only_sermons) { :type == 'Sermons' }
  subset(:ignore_unavailable) { :title != 'Unavailable' }
  #subset(:cleanup) {(:title != 'Unavailable') AND (:type == 'Sermons')}  # causes syntax error

  #def initialize
    #super
    #self.only_sermons.ignore_unavailable
  #end

  def validate
    super
    validates_presence [
      :title,
      :scripture,
      :reader,
      :series,
      :preacher,
      :engineer,
      :processor,
      :disk
    ]

    validates_unique :date
  end
end

