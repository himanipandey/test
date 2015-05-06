class LandmarkMapAdded < ActiveRecord::Migration
  def up
  	#execute "create table landmark_types (id int(11) primary key auto_increment, name varchar(60), display_name varchar(100), description varchar(100), enable tinyint(4) default 1, user_maintained tinyint(4) default 0, page_active tinyint(4) default 0)"
  
	execute "create table landmark_map_data (id int(11) primary key, lat_long_data varchar(5000), svg_data varchar(5000), center_boundary varchar(5000), boundary_type varchar(255), boundaryEncode varchar(5000))"


	#execute "INSERT INTO  landmark_types (name, display_name, description, enable, user_maintained, page_active) values ('major_road', 'major_road', 'Major Road', 1, 0, 0)"

	#execute "INSERT INTO  landmark_types (name, display_name, description, enable, user_maintained, page_active) values ('minor_road', 'minor_road', 'Minor Road', 1, 0, 0)"

	execute "INSERT INTO  landmark_types (name, display_name, description, enable, user_maintained, page_active) values ('River', 'River', 'River', 1, 0, 0)"

	execute "INSERT INTO  landmark_types (name, display_name, description, enable, user_maintained, page_active) values ('Canal', 'Canal', 'Canal', 1, 0, 0)"

	execute "INSERT INTO  landmark_types (name, display_name, description, enable, user_maintained, page_active) values ('Drain', 'Drain', 'Drain', 1, 0, 0)"

	execute "INSERT INTO  landmark_types (name, display_name, description, enable, user_maintained, page_active) values ('Railway Line', 'Railway Line', 'Railway Line', 1, 0, 0)"

	execute "INSERT INTO  landmark_types (name, display_name, description, enable, user_maintained, page_active) values ('Metro Line', 'Metro Line', 'Metro Line', 1, 0, 0)"

	#execute "INSERT INTO  landmark_types (name, display_name, description, enable, user_maintained, page_active) values ('Metro Station', 'Metro Station', 'Metro Station', 1, 0, 0)"

	execute "INSERT INTO  landmark_types (name, display_name, description, enable, user_maintained, page_active) values ('Industrial', 'Industrial', 'Industrial', 1, 0, 0)"

	#execute "INSERT INTO  landmark_types (name, display_name, description, enable, user_maintained, page_active) values ('Commercial', 'Commercial', 'Commercial', 1, 0, 0)"


	execute "INSERT INTO  landmark_types (name, display_name, description, enable, user_maintained, page_active) values ('Agricultural', 'Agricultural', 'Agricultural', 1, 0, 0)"


	execute "INSERT INTO  landmark_types (name, display_name, description, enable, user_maintained, page_active) values ('Residential Land', 'Residential Land', 'Residential Land', 1, 0, 0)"


	execute "INSERT INTO  landmark_types (name, display_name, description, enable, user_maintained, page_active) values ('Green Belt', 'Green Belt', 'Green Belt', 1, 0, 0)"


	execute "alter table landmarks add column boundary varchar(5000)"

	execute "alter table landmarks add column center_boundary varchar(500)"

	execute "alter table landmarks add column future_flag tinyint(4)"

	execute "alter table landmarks add column svg_data varchar(5000)"

	execute "alter table landmarks add column boundary_type varchar(255)"

	execute "alter table landmarks add column boundaryEncode varchar(5000)"

  end
  def down
  	execute "drop table landmark_types"
 	execute "drop table landmark_map_data"
 	execute "ALTER TABLE landmarks DROP boundary"
 	execute "ALTER TABLE landmarks DROP center_boundary"
 	execute "ALTER TABLE landmarks DROP future_flag"
 	execute "ALTER TABLE landmarks DROP svg_data"
 	execute "ALTER TABLE landmarks DROP boundary_type"
 	execute "ALTER TABLE landmarks DROP boundaryEncode"
  end
end
