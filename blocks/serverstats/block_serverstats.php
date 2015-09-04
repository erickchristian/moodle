<?php

class block_serverstats extends block_base {
    public function init() {
        $this->title = get_string('serverstats', 'block_serverstats');
    }

    public function get_content() {
        global $DB;
             
        if ($this->content !== null) {
            return $this->content;
        }
        
        $sql = 'SELECT COUNT(id) FROM {course}';
        $coursCount = $DB->count_records_sql($sql);
        
        $sql = 'SELECT COUNT(DISTINCT(u.id)) FROM {user} as u
                LEFT JOIN
                {role_assignments} AS ra on u.id=ra.userid
                WHERE ra.roleid = 3 ';
        $teacherCount = $DB->count_records_sql($sql);
        
        $sql = 'SELECT COUNT(DISTINCT(u.id)) FROM {user} as u
                LEFT JOIN
                {role_assignments} AS ra on u.id=ra.userid
                WHERE ra.roleid = 5 ';
        $studentCount = $DB->count_records_sql($sql);
        
        $ut = $this->linuxUptime();
        
        $this->content =  new stdClass;
        
        $this->content->text .= "Time since last reboot:<BR>";
        $this->content->text .= "$ut[0] days, $ut[1] hours, $ut[2] minutes";
        $this->content->text .= "<BR><BR>";
        $this->content->text .= "$coursCount Courses";
        $this->content->text .= "<BR><BR>";
        $this->content->text .= "$teacherCount Teachers";
        $this->content->text .= "<BR><BR>";
        $this->content->text .= "$studentCount Students";
        
        $this->content->footer = '';
        return $this->content;

    }

    function linuxUptime() {
        //http://www.danielkassner.com/2011/08/24/linux-uptime
        $ut = strtok( exec( "cat /proc/uptime" ), "." );
        $days = sprintf( "%2d", ($ut/(3600*24)) );
        $hours = sprintf( "%2d", ( ($ut % (3600*24)) / 3600) );
        $min = sprintf( "%2d", ($ut % (3600*24) % 3600)/60  );
        $sec = sprintf( "%2d", ($ut % (3600*24) % 3600)%60  );
        return array( $days, $hours, $min, $sec );
    }
}
?>
