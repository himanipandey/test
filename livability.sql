# insert distances
insert into project_distances (city_id, lnp_id, project_id, distance, place_type_id, priority) select lnp.city_id, lnp.id, rp.PROJECT_ID, ((ACOS(SIN(rp.LATITUDE * PI() / 180) * SIN(lnp.latitude * PI() / 180) + COS(rp.LATITUDE * PI() / 180) * COS(lnp.latitude * PI() / 180) * COS((rp.LONGITUDE - lnp.longitude) * PI() / 180)) * 180 / PI()) * 60 * 1.1515 * 1609.34) AS distance, lnp.place_type_id, lnp.priority from resi_project rp inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on l.SUBURB_ID = s.SUBURB_ID inner join locality_near_places lnp on s.CITY_ID = lnp.city_id where rp.version = 'Website' and rp.LONGITUDE not in (0,1) and rp.LATITUDE not in (0,1) and lnp.status = 'Active';

# insert pids in final table
insert into project_livability_ranking (project_id) select project_id from resi_project rp inner join locality l on l.LOCALITY_ID = rp.LOCALITY_ID inner join suburb s on l.SUBURB_ID = s.SUBURB_ID where rp.version = 'Website' and s.CITY_ID = 18;


# update school rating in final table
# distance min = 500 m
# no distance max
# no count
# function = sum(e^-(min(dis, 500)/priority) / max for all project

# get max using
select project_id, sum(exp(-(greatest(500, distance)/1000))/priority) ranking from project_distances where city_id = 20 and place_type_id = 1 group by project_id order by ranking desc limit 1;

# update using
update project_livability_ranking plr inner join (select project_id, sum(exp(-(greatest(500, distance)/1000))/priority)/2.145459458234945 ranking from project_distances where city_id = 20 and place_type_id = 1 group by project_id) t on plr.project_id = t.project_id set plr.school = t.ranking;



# update hospital rating in final table
# distance min = 500 m
# no distance max
# no count
# function = sum(e^-(min(dis, 500)/priority) / max for all project

# get max using
select project_id, sum(exp(-(greatest(500, distance)/1000))/priority) ranking from project_distances where city_id = 20 and place_type_id = 2 group by project_id order by ranking desc limit 1;

# update using
update project_livability_ranking plr inner join (select project_id, sum(exp(-(greatest(500, distance)/1000))/priority)/2.005893332090918 ranking from project_distances where city_id = 20 and place_type_id = 2 group by project_id order by ranking) t on plr.project_id = t.project_id set plr.hospital = t.ranking;

# play school
# max
select project_id, sum(exp(-(greatest(500, distance)/1000))/priority) ranking from project_distances where city_id = 20 and place_type_id = 14 group by project_id order by ranking desc limit 1;
# update
update project_livability_ranking plr inner join (select project_id, sum(exp(-(greatest(500, distance)/1000))/priority)/2.764461645470729 ranking from project_distances where city_id = 20 and place_type_id = 14 group by project_id) t on plr.project_id = t.project_id set plr.play_school = t.ranking;


# metro station
# max
select project_id, sum(exp(-(greatest(500, distance)/1000))/priority) ranking from project_distances where city_id = 20 and place_type_id = 7 group by project_id order by ranking desc limit 1;

# update
update project_livability_ranking plr inner join (select project_id, sum(exp(-(greatest(500, distance)/1000))/priority)/1.374879780998229 ranking from project_distances where city_id = 20 and place_type_id = 1 group by project_id) t on plr.project_id = t.project_id set plr.play_school = t.ranking;




# lifts per flat

# get max using
select rp.PROJECT_ID, plc.LIFT_COUNT, sum(supply), if(isnull(plc.LIFT_COUNT/sum(supply)), 0, plc.LIFT_COUNT/sum(supply)) lifts from resi_project rp inner join resi_project_phase rpp on rp.PROJECT_ID = rpp.PROJECT_ID and rpp.version = 'Website' and rp.version = 'Website' inner join listings l on rpp.PHASE_ID = l.phase_id and l.status = 'Active' inner join project_supplies ps on l.id = ps.listing_id and ps.version = 'Website' inner join (select rp.PROJECT_ID, sum(NO_OF_LIFTS) LIFT_COUNT from resi_project_tower_details rptd inner join resi_project rp on rptd.PROJECT_ID = rp.PROJECT_ID and rp.version = 'Website' inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on l.SUBURB_ID = s.SUBURB_ID where s.CITY_ID = 20 group by PROJECT_ID having LIFT_COUNT > 0) plc on rp.PROJECT_ID=plc.PROJECT_ID inner join (select rpp.PHASE_ID, rpp.PHASE_TYPE from resi_project_phase rpp inner join resi_project_phase rpp1 on rpp.PROJECT_ID = rpp1.PROJECT_ID and rpp1.version = 'Website' and rpp.version = 'Website' group by rpp.PHASE_ID having count(distinct rpp1.PHASE_TYPE) = 1 or rpp.PHASE_TYPE = 'Actual') t1 on rpp.PHASE_ID = t1.PHASE_ID group by rp.PROJECT_ID order by lifts desc limit 1;

# update using
update project_livability_ranking plr inner join (select rp.PROJECT_ID, plc.LIFT_COUNT, sum(supply), if(isnull(plc.LIFT_COUNT/sum(supply)), 0, plc.LIFT_COUNT/sum(supply))/0.3854 lifts from resi_project rp inner join resi_project_phase rpp on rp.PROJECT_ID = rpp.PROJECT_ID and rpp.version = 'Website' and rp.version = 'Website' inner join listings l on rpp.PHASE_ID = l.phase_id and l.status = 'Active' inner join project_supplies ps on l.id = ps.listing_id and ps.version = 'Website' inner join (select rp.PROJECT_ID, sum(NO_OF_LIFTS) LIFT_COUNT from resi_project_tower_details rptd inner join resi_project rp on rptd.PROJECT_ID = rp.PROJECT_ID and rp.version = 'Website' inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on l.SUBURB_ID = s.SUBURB_ID where s.CITY_ID = 20 group by PROJECT_ID having LIFT_COUNT > 0) plc on rp.PROJECT_ID=plc.PROJECT_ID inner join (select rpp.PHASE_ID, rpp.PHASE_TYPE from resi_project_phase rpp inner join resi_project_phase rpp1 on rpp.PROJECT_ID = rpp1.PROJECT_ID and rpp1.version = 'Website' and rpp.version = 'Website' group by rpp.PHASE_ID having count(distinct rpp1.PHASE_TYPE) = 1 or rpp.PHASE_TYPE = 'Actual') t1 on rpp.PHASE_ID = t1.PHASE_ID group by rp.PROJECT_ID) t on plr.PROJECT_ID = t.PROJECT_ID set plr.lifts = t.lifts;




# security
# 1 or 0

update project_livability_ranking plr inner join (select rp.PROJECT_ID from resi_project_amenities rpa inner join resi_project rp on rpa.PROJECT_ID = rp.PROJECT_ID and rp.version = 'Website' inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID where AMENITY_ID = 11 and s.CITY_ID = 20) t on plr.PROJECT_ID = t.PROJECT_ID set plr.security = 1;



# other amenity count

# get max using
select rp.PROJECT_ID, count(distinct AMENITY_ID) other_amenity_count from resi_project_amenities rpa inner join resi_project rp on rpa.PROJECT_ID = rp.PROJECT_ID and rp.version = 'Website' inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID where AMENITY_ID != 11 and s.CITY_ID = 20 group by rpa.PROJECT_ID order by other_amenity_count desc limit 1;

# update using
update project_livability_ranking plr inner join (select rp.PROJECT_ID, count(AMENITY_ID)/25 other_amenity_count from resi_project_amenities rpa inner join resi_project rp on rpa.PROJECT_ID = rp.PROJECT_ID and rp.version = 'Website' inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID where AMENITY_ID != 11 and s.CITY_ID = 20 group by rpa.PROJECT_ID) t on plr.PROJECT_ID = t.PROJECT_ID set plr.other_amenity_count = t.other_amenity_count;



# values

# for a city
select t.project_id, rp.PROJECT_NAME, l.label, t.livability from resi_project rp inner join (select project_id, ((0.25*school)+(0.25*hospital)+(0.05*lifts)+(0.05*security)+(0.4*other_amenity_count))*10 livability from project_livability_ranking order by livability) t on rp.PROJECT_ID = t.project_id and rp.version = 'Website' inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID where s.CITY_ID = 20 order by t.livability desc limit 10;

# locality avegare

select label, avg(livability) from (select t.project_id, rp.PROJECT_NAME, l.label, t.livability from resi_project rp inner join (select project_id, ((0.25*school)+(0.25*hospital)+(0.05*lifts)+(0.05*security)+(0.4*other_amenity_count))*10 livability from project_livability_ranking order by livability) t on rp.PROJECT_ID = t.project_id and rp.version = 'Website' inner join locality l on rp.LOCALITY_ID = l.LOCALITY_ID inner join suburb s on s.SUBURB_ID = l.SUBURB_ID where s.CITY_ID = 20) t group by label order by avg(livability) desc;