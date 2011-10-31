# CTKSound.org Model classes
# Author: Kevin Gustavson

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

class Sermon < Sequel::Model(:sermons)
  plugin :validation_helpers
  subset(:published) {:published != nil}
  subset(:unpublished) {:published == nil}

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

class Teaching < Sequel::Model(:aces)
  plugin :validation_helpers
  subset(:published) {:published != nil}
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

class Dedication < Sequel::Model(:dedications)
  plugin :validation_helpers
  subset(:published) {:published != nil}
  subset(:unpublished) {:published == nil}

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

class Portrait < Sequel::Model(:portraits)
  plugin :validation_helpers
  subset(:published) {:published != nil}
  subset(:unpublished) {:published == nil}

  def validate
    super
    validates_presence [
      :series,
      :speaker
    ]

    validates_unique :date
  end
end

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

