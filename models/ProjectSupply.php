function projectSupplyForProjectPage($projectId) {
        $result = array();
        $query = "select rpp.PHASE_NAME, rpp.LAUNCH_DATE, rpp.COMPLETION_DATE, rpp.submitted_date, rpp.project_id, rpp.BOOKING_STATUS_ID,
            psm.display_name as construction_status, ls.phase_id, rpo.bedrooms as no_of_bedroom, ps.supply, ps.launched, 
            pa.availability, pa.comment, pa.effective_month, rpo.option_type as project_type,ls.id as listing_id
            from 
             " . self::table_name() . " ps 
             inner join " . ProjectAvailability::table_name() . " pa on (ps.id=pa.project_supply_id and ps.version = 'PreCms')
             inner join listings ls on ps.listing_id = ls.id
             inner join resi_project_options rpo on rpo.options_id = ls.option_id    
             inner join 
                (select ps.id, max(pa.effective_month) mon 
                    from " . self::table_name() . " ps inner join " . ProjectAvailability::table_name() . " pa 
                    on ps.id=pa.project_supply_id
                    inner join listings ls on (ps.listing_id = ls.id and ls.listing_category = 'Primary' and ls.status = 'Active')  
                    left join " . ResiProjectPhase::table_name() . " rpp on ls.phase_id = rpp.PHASE_ID
                    join project_status_master psm on rpp.construction_status = psm.id    
                    where rpp.project_id = $projectId and rpp.version = 'Cms' and ps.version = 'Cms' and ps.version = 'PreCms' and rpp.status = 'Active' group by ps.id

                 ) t 
                on ps.id=t.id and pa.effective_month=t.mon 
             left join " . ResiProjectPhase::table_name() . "  rpp on (ls.phase_id = rpp.PHASE_ID and rpp.version = 'Cms')
             join project_status_master psm on rpp.construction_status = psm.id    
       union 
            select rpp.PHASE_NAME, rpp.LAUNCH_DATE, 
                rpp.COMPLETION_DATE, rpp.submitted_date, rpp.project_id,rpp.BOOKING_STATUS_ID, psm.display_name as construction_status, ls.phase_id, rpo.bedrooms as no_of_bedroom, ps.supply,
                ps.launched, pa.availability, pa.comment, pa.effective_month, 
                rpo.option_type as project_type,ls.id as listing_id 
            from 
                project_supplies ps left join project_availabilities pa on (ps.id=pa.project_supply_id and ps.version = 'PreCms')
            inner join listings ls on (ps.listing_id = ls.id  and ls.listing_category = 'Primary' and ls.status = 'Active')          
            left join resi_project_phase rpp on (ls.phase_id = rpp.PHASE_ID)
            join project_status_master psm on rpp.construction_status = psm.id
            inner join resi_project_options rpo on rpo.options_id = ls.option_id
          where pa.id is null and ps.version = 'PreCms' and rpp.project_id = $projectId and rpp.version = 'Cms' and rpp.status = 'Active'";
        $data = self::find_by_sql($query);
        foreach ($data as $value) {
            $entry = array();
            $entry['PHASE_NAME'] = $value->phase_name;
            $entry['LAUNCH_DATE'] = $value->launch_date;
            $entry['COMPLETION_DATE'] = $value->completion_date;
            $entry['submitted_date'] = $value->submitted_date;
            $entry['PROJECT_ID'] = $value->project_id;
            $entry['PHASE_ID'] = $value->phase_id;
            $entry['NO_OF_BEDROOMS'] = $value->no_of_bedroom;
            $entry['NO_OF_FLATS'] = $value->supply;
            $entry['LAUNCHED'] = $value->launched;
            $entry['AVAILABLE_NO_FLATS'] = $value->availability;
            $entry['EDIT_REASON'] = $value->comment;
            $entry['SUBMITTED_DATE'] = $value->effective_month;
            $entry['PROJECT_TYPE'] = $value->project_type;
            $entry['BOOKING_STATUS_ID'] = $value->booking_status_id;
            $entry['CONSTRUCTION_STATUS'] = $value->construction_status;
            $entry['LISTING_ID'] = $value->listing_id;
            $result[] = $entry;
        }
        return $result;
    }