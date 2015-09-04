<?php
/*
*Script:      emailfilter.php
*Scripter:    James Krippes
*Date:        01/05/2011
*Abstract:    this script queries the mysql database to verifiy that students are not emailing each other.
*
*Edited
*Scripter:    Erick Christian
*Date:        09/03/2015
*Changes:     this script was accessing the database directly using hard-coded DB info and credentials.
*             the getroleid function has been updated to make use of Moodle's own data manipulation API.
*/

function getroleid($a)
{
    global $DB;
    
    // Formulate Query
    $sql = "SELECT t1.username as username,
            MIN(t2.roleid) as roleid
            FROM {user} t1 
            INNER JOIN {role_assignments} t2 on t1.id=t2.userid 
            WHERE t1.id = :userid";
    $params = array('userid' => $a);
    
    // Perform Query
    $result = $DB->get_record_sql($sql, $params);
    
    // Use result
    return $result->roleid;
}

function gfpsemailcheck($user1id,$user2id)
{
    if(isset($_SESSION['roleiduser1'])) //set session var for logon user
    {
        //echo("Session already set");
        //$_SESSION['roleiduser1'] = getroleid($user1id);
    }else {
        //echo("Setting Session");
        $_SESSION['roleiduser1'] = getroleid($user1id);
    }
    if($_SESSION['roleiduser1']!=2)//if they're not a Course Creator,
                                   //AKA teacher, we assume they are a student,
                                   //and look at who they are talking to.
    {
        if($user2id!=0)//they have picked someone to talk with
        {
            $user2roleid=getroleid($user2id);
            if($user2roleid!=2)//a student is talking with another student.
            {
                error("Students can only send Moodle messages to teachers.");
            }
        }
    }
}
//echo("Hello World");
?>