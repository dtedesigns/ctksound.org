# CTKSound.org Model classes
# Author:: Kevin Gustavson
# Copyright:: Copyright (c)2011 Kevin D. Gustavson. All rights reserved.
# License::   On request only.

# This class modifies the Sequel::Model superclass for these models.
class Sequel::Model
  def validate
    super
    validates_presence [
      :date,
    ]
  end

  def before_create
    self.created_at ||= Time.now # TODO if exists
    super
  end

  def before_update
    self.updated_at ||= Time.now # TODO if exists
    super
  end
end

# This is the Sermon model (sermons table)
class Sermon < Sequel::Model(:sermons)
  plugin :validation_helpers
  # The published sermons filter
  subset(:published) {:published != nil}
  # The unpublished sermons filter
  subset(:unpublished) {:published == nil}
  #subset(:cleanup) {:title != 'Unavailable', :type == 'Sermons'}  # causes syntax error

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

# This is the Identity model (identities table)
class Identity < Sequel::Model
  # put stuff here
end
