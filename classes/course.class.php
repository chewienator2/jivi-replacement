<?php 
class Course extends Database{
    
    public $course = array();
    
    public function __construct(){
        parent::__construct();
    }
    
    //get list of ALL courses available 
    public function getCourses(){
        $query = "SELECT 
                        id,name,code
                    FROM course
                    WHERE active = 1
                    ORDER BY name ASC";
        $statement = $this->connection->prepare($query);
        $statement->execute();
        
        $result = $statement->get_result();
        
        //loop thru query results
        while( $row = $result->fetch_assoc() ){
            array_push( $this->course, $row );
        }
        return $this->course;
    }
    
    //get list of courses for curriculum 
    public function getCoursesForCurrirulum($bachelor_id){
        $query = "SELECT 
                        id,name,code 
                    FROM course 
                    WHERE id NOT IN (
                        SELECT DISTINCT(course_id) AS id FROM curriculum WHERE bachelor_id =?
                    ) AND active = 1 
                    ORDER BY name ASC";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('i', $bachelor_id);
        $statement->execute();
        
        $result = $statement->get_result();
        
        //loop thru query results
        while( $row = $result->fetch_assoc() ){
            array_push( $this->course, $row );
        }
        return $this->course;
    }
    
    //get specific course by ID
    public function getCourse($id){
        $query = "SELECT * FROM course WHERE id = ? AND active = 1";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();
        
        $result = $statement->get_result();
        
        //get the result
        $row = $result->fetch_assoc();
        $this->course = $row;
        
        return $this->course;
        
    }

    
    //get courses available to add on timetable depending on bachelor ID students
    public function getCoursesForTimetableStd($id){
        
        $query = "SELECT 
                        course.name, 
                        course.code,
                        course.id AS course_id,
                        cgroup.id AS group_id, 
                        cgroup.name AS group_name, 
                        session.day, 
                        session.time_block, 
                        room.name AS room_name 
                    FROM course
                    JOIN curriculum ON course.id = curriculum.course_id 
                    JOIN `group` AS cgroup ON cgroup.course_id = course.id
                    JOIN session ON session.group_id = cgroup.id
                    JOIN room ON room.id = session.room_id
                    WHERE bachelor_id = ? AND course.active = 1 
                    ORDER BY course.name ASC";
        
        $statement = $this->connection->prepare($query);
        $statement->bind_param('i', $id);
        $statement->execute();
        
        $result = $statement->get_result();
        
        $result_array = array();
        $session_array = array();
        $last_id = 0;
        //loop thru query results
        while( $row = $result->fetch_assoc() ){
            
            //first we need the group info on the array
            if($row['group_id'] != $last_id || $last_id == 0){
                $result_array[$row['group_id']] = array(
                                'course_name' => $row['name'], 
                                'course_code'=>$row['code'], 
                                'course_id'=>$row['course_id'],
                                'group_name'=>$row['group_name'], 
                                'sessions'=>array()
                            );
                //lets update the last id with current group id
                $last_id = $row['group_id'];
            }
            
            //check if we are looking at the same group and if so, add a new session to array
            if($row['group_id'] == $last_id || $last_id == 0){
                $result_array[$row['group_id']]['sessions'][] = array('day'=>$row['day'], 'time_block'=>$row['time_block'],'room'=>$row['room_name']);
            }
        }
        
        $this->course = $result_array;
        return $this->course;
    }
    
    //get courses available to teachers
    public function getCoursesForTimetableTch(){
        
        $query = "SELECT 
                        course.name, 
                        course.code,
                        course.id AS course_id,
                        cgroup.id AS group_id, 
                        cgroup.name AS group_name, 
                        session.day, 
                        session.time_block, 
                        room.name AS room_name 
                    FROM course
                    JOIN curriculum ON course.id = curriculum.course_id 
                    JOIN `group` AS cgroup ON cgroup.course_id = course.id
                    JOIN session ON session.group_id = cgroup.id
                    JOIN room ON room.id = session.room_id
                    WHERE course.active = 1 
                    ORDER BY course.name ASC";
        
        $statement = $this->connection->prepare($query);
        //$statement->bind_param('i', $id);
        $statement->execute();
        
        $result = $statement->get_result();
        
        $result_array = array();
        $session_array = array();
        $last_id = 0;
        //loop thru query results
        while( $row = $result->fetch_assoc() ){
            
            //first we need the group info on the array
            if($row['group_id'] != $last_id || $last_id == 0){
                $result_array[$row['group_id']] = array(
                                'course_name' => $row['name'], 
                                'course_code'=>$row['code'], 
                                'course_id'=>$row['course_id'],
                                'group_name'=>$row['group_name'], 
                                'sessions'=>array()
                            );
                //lets update the last id with current group id
                $last_id = $row['group_id'];
            }
            
            //check if we are looking at the same group and if so, add a new session to array
            if($row['group_id'] == $last_id || $last_id == 0){
                $result_array[$row['group_id']]['sessions'][] = array('day'=>$row['day'], 'time_block'=>$row['time_block'],'room'=>$row['room_name']);
            }
        }
        
        $this->course = $result_array;
        return $this->course;
    }
    
    //create a new course
    public function create($name, $overview, $learning_outcomes, $code, $hours_per_week, $credits){
        $query = "INSERT INTO course 
                        (name, overview, learning_outcomes, code, hours_per_week, credits, active) 
                    VALUES 
                        (?,?,?,?,?,?,1)";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('ssssss', $name, $overview, $learning_outcomes, $code, $hours_per_week, $credits);
        
        $succes = $statement->execute() ? true : false;
        
        return $succes;
    }
    
    //edit a course
    public function edit($id, $name, $overview, $learning_outcomes, $code, $hours_per_week, $credits){
        $query = "UPDATE course SET 
                        name = ?,
                        overview = ?,
                        learning_outcomes = ?,
                        code = ?,
                        hours_per_week = ?,
                        credits = ? 
                    WHERE id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('ssssssi', $name, $overview, $learning_outcomes, $code, $hours_per_week, $credits, $id);
        
        $succes = $statement->execute() ? true : false;
        
        return $succes;
    }
    
    //"delete" deactivate a course
    public function deactivate($id){
        $query = "UPDATE course SET active = 0 WHERE id = ?";
        $statement = $this->connection->prepare($query);
        $statement->bind_param('i', $id);
        
        $success = $statement->execute() ? true : false;
        
        return $success;
    }
}

?>