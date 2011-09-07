Sequel.migration do
  up do
    create_table(:aces) do
      primary_key :id
      String :series, :size=>255
      String :title, :size=>255
      String :teacher, :size=>255
      String :comment, :size=>255
      Date :date
      Integer :disk
      String :spl, :size=>255
      DateTime :published
      DateTime :created_at
      DateTime :updated_at
    end
    
    create_table(:dedications) do
      primary_key :id
      Date :date
      String :official, :size=>255
      String :child, :size=>255
      String :comment, :size=>255
      String :type, :default=>"dedication", :size=>63
      String :notice_sent
      DateTime :published
      DateTime :created_at
      DateTime :updated_at
    end
    
    create_table(:labels) do
      primary_key :id
      Integer :sermon_id, :null=>false
      Integer :index, :null=>false
      String :title, :null=>false, :size=>256
      Integer :start, :null=>false
      Integer :end, :null=>false
    end
    
    create_table(:portraits) do
      primary_key :id
      Date :date, :null=>false
      String :speaker, :size=>255
      String :comment, :size=>255
      String :notice_sent
      DateTime :published
      DateTime :created_at
      DateTime :updated_at
    end
    
    create_table(:roles, :ignore_index_errors=>true) do
      primary_key :id
      String :name, :null=>false, :size=>32
      String :description, :null=>false, :size=>255
      
      index [:name], :unique=>true, :name=>:uniq_name
    end
    
    create_table(:roles_users, :ignore_index_errors=>true) do
      Integer :user_id, :null=>false
      Integer :role_id, :null=>false
      
      primary_key [:user_id, :role_id]
      
      index [:role_id], :name=>:fk_role_id
    end
    
    create_table(:schedule) do
      primary_key :id
      Date :date, :null=>false
      String :name, :null=>false, :size=>256
      TrueClass :alert_sent, :null=>false, :default=>false
      DateTime :created_at
      DateTime :updated_at
    end
    
    create_table(:sermons) do
      primary_key :id
      String :series, :size=>255
      String :title, :size=>255
      String :preacher, :size=>255
      String :scripture, :size=>255
      String :reader, :size=>255
      Date :date
      Integer :track
      Integer :year
      Integer :disk
      String :type, :default=>"Sermon", :size=>20, :null=>false
      String :engineer, :size=>255
      String :processor, :size=>255
      String :hymns_spl, :size=>255
      String :sermon_spl, :size=>255
      String :notes, :text=>true
      DateTime :published
      DateTime :email_sent
      DateTime :created_at
      DateTime :updated_at
    end
    
    create_table(:user_tokens, :ignore_index_errors=>true) do
      primary_key :id
      Integer :user_id, :null=>false
      String :user_agent, :null=>false, :size=>40
      String :token, :null=>false, :size=>32
      Integer :created, :null=>false
      Integer :expires, :null=>false
      
      index [:user_id], :name=>:fk_user_id
      index [:token], :unique=>true, :name=>:uniq_token
    end
    
    create_table(:users, :ignore_index_errors=>true) do
      primary_key :id
      String :email, :null=>false, :size=>127
      String :username, :null=>false, :size=>32
      String :password, :fixed=>true, :null=>false, :size=>50
      Integer :logins, :default=>0, :null=>false
      Integer :last_login
      
      index [:email], :unique=>true, :name=>:uniq_email
      index [:username], :unique=>true, :name=>:uniq_username
    end
  end
  
  down do
    drop_table(:aces, :dedications, :labels, :portraits, :roles, :roles_users, :schedule, :sermons, :user_tokens, :users)
  end
end
