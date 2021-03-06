<?php
session_start();
//include session check
include('../session_check.php');

//include the autoloader class
include('../autoloader.php');

//we need the bachelors list for this page
//$bachelor = new Bachelor();
//$bachelor_list = $bachelor->getBachelors();

//lets check if they want to edit or create
if($_GET['a'] == 'e'){ 
    //lets get them the information they want
    $course = new Course();
    $info = $course->getCourse($_GET['id']);
    $group = new Group();
    $groups_list = $group->getGroupsFromCourse($_GET['id']);
    $page_title = "Edit Course";
    $accounts = new Account();
    $teacher_accounts = $accounts->getTeacherAccounts();

}elseif($_GET['a'] == 'n'){ //create a new one
    $page_title = "Create Course";
}


?>
        
<!DOCTYPE html>
<html>
    <?php include('../includes/head.php'); ?>
<body>
<link href="css/style.css" rel="stylesheet">
    <div id="wrapper">
        <!-- Sidebar -->
        <div id="sidebar-wrapper">
            <?php include 'includes/navbar.php'; ?>  
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
            <div class="container-fluid">
                <h2>Course</h2>
                <p>Here is all information related to this course</p>
                <a href="#menu-toggle" class="btn btn-secondary" id="menu-toggle">Toggle Menu</a>
            </div>
            <div class="row pt-3">
                <div class="col-6">
                    <form id="main-form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" value="<?php echo $info['name']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="overview">Overview</label>
                            <textarea class="form-control" name="overview" id="overview" rows="4"><?php echo $info['overview']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="learning_outcomes">Learning Outcomes</label>
                            <textarea class="form-control" name="learning_outcomes" id="learning_outcomes" rows="4"><?php echo $info['learning_outcomes']; ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="cricos">Program code</label>
                            <input type="text" name="code" class="form-control" id="code" value="<?php echo $info['code']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="hours_per_week">Hours per week</label>
                            <input type="text" name="hours_per_week" class="form-control" id="hours_per_week" value="<?php echo $info['hours_per_week']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="credits">Credits</label>
                            <input type="text" name="credits" class="form-control" id="credits" value="<?php echo $info['credits']; ?>" required>
                        </div>
                        <input type="hidden" name="a" value="<?php echo $_GET['a']; ?>">
                        <input type="hidden" name="h" value="course">
                        
                        <?php if($_GET['a'] == 'e'){ ?>
                        <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
                        <?php  }  ?>
                        
                        <button class="btn btn-primary mt-2" id="save-btn" type="submit"/>Save</button>
                    </form>
                </div>
                <div class="col-6">
                    <div class="container">
                        <h6>Groups</h6>
                        
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createGroup">Create new group</button>
                        
                        <div class="container-fluid pt-3">
                            <div class="list-group w-100 curriculum-list">
                                <?php 
                                if(count($groups_list) >0){
                                    foreach($groups_list as $group){ 
                                ?>
                                <div class="list-group-item flex-column align-items-start <?php echo 'c'.$group['id']; ?>">
                                    <div class="row">
                                        <div class="col justify-content-between">
                                            <h6 class="mb-1"><a href="/admin/group.php?a=e&id=<?php echo $group['id']; ?>"><?php echo $group['course_name'].' '.$group['name']; ?></a></h6>
                                        </div>
                                        <div class="col d-flex justify-content-end align-self-center">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button type="button" class="btn btn-danger" onclick="group('d',<?php echo $group['id']; ?>);"><i class="fa fa-minus" aria-hidden="true"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <?php } }?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->
        <!-- add course to curriculum Modal -->
        <div class="modal fade" id="createGroup" tabindex="-1" role="dialog" aria-labelledby="addGroup" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addGroup">Create course group</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="group-form" method="post">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" id="name" required>
                            </div>
                            <div class="form-group">
                                <label for="teacher_id">Teacher</label>
                                <select name="teacher_id" class="form-control" required>
                                    <option></option>
                                    <?php 
                                    if(count($teacher_accounts)>0){
                                        
                                        foreach($teacher_accounts AS $teacher){
                                            echo '<option value="'.$teacher['id'].'">'.$teacher['name'].' '.$teacher['surname'].'</option>';
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                            <input type="hidden" name="a" value="n">
                            <input type="hidden" name="id" value="<?php echo $info['id']; ?>">
                         </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <button id="submitGroup" type="button" onclick="submitGroup()" class="btn btn-primary">Create group</button>
                    </div>
                </div>
            </div>
        </div>
    
        <script type="text/javascript" src="js/form_submit.js"></script>
        <script type="text/javascript" src="/js/common.js"></script>
        <script type="text/javascript">
            
            //this function sends the data to create a new group
            function submitGroup(){
                
                let formData = $('#group-form').serialize();
                $.ajax({
                    url: '/admin/ajax/group.ajax.php',
                    method: 'post',
                    dataType: 'json',
                    data: formData,
                }).done( (response) => {
                    if(response.success == true){
                        $('#createGroup').modal('toggle');
                
                        /* modal fix (not working completely) */
                        $('body').removeClass('modal-open'); 
                        $('.modal-backdrop').remove();
                        /* /fix */
                        if(response.div.length > 0){
                            //we have to append the new course
                            $('.'+response.div).append(response.group);
                        }
                    }
                    //popup the notification message
                    msgHandler(response.success, response.msg);
                });
            }
            
            //function to delete groups
            function group(a, group_id){
                $.ajax({
                    url: '/admin/ajax/group.ajax.php',
                    method: 'post',
                    dataType: 'json',
                    data: {a: a, group_id: group_id },
                }).done( (response) => {
                    if(response.success == true){
                        //if delete was successfull
                        if(response.div.length > 0 && a =='d'){
                            //remove from current course
                            $('.c'+group_id).remove();
                        }
                    }
                    //popup the notification message
                    msgHandler(response.success, response.msg);
                });
            }
        </script>
    </div>
    <!-- /#wrapper -->
    <?php include 'includes/footer.php'; ?>

</body>

</html>