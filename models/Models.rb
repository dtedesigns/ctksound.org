# CTKSound.org Model classes
# Author:: Kevin Gustavson
# Copyright:: Copyright (c)2011 Kevin D. Gustavson. All rights reserved.
# License::   On request only.

require 'models/Sermon'
require 'models/Teaching'
require 'models/Dedication'
require 'models/Portrait'
require 'models/Label'

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

# This is the Identity model (identities table)
class Identity < Sequel::Model
  # put stuff here
end

