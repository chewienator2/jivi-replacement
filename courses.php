<?php
session_start();
//include session check
include('session_check.php');

//include the autoloader class
include('autoloader.php');

//we create an object of course
$course = new Course();
//let's get the courses list
$myCourse = $course->getCourses(); 

$page_title = "Subjects";
?>
<!doctype html>
<html>
    <?php include('includes/head.php'); ?>
    <body>
        <?php include('includes/navbar.php'); ?>
        <!-- container -->
        <div class="container-fluid">
            <div class="row">
                <!-- first page -->
                <div class="col-md-12 col-lg-4 p-3 animated page1">
                    <h2>Subjects</h2>
                    <div class="container-fluid">
                        <div class="row">
                            <div class="d-flex">
                                <input type="text" id="search" onkeyup="search()" placeholder="Search course" name="search">
                                <button> <i class="fa fa-search"> </i> </button>
                            </div>
                        </div>
                        <div class="row pt-3">
                            <div class="list-group w-100"><!-- main list container -->
                                <?php foreach($myCourse AS $course){ //loop thru results array ?>
                                <div class="list-group-item list-group-item-action flex-column align-items-start searchable" data-name="<?php echo $course['name'].' - '.$course['code']; ?>">
                                    <div class="row">
                                        <div class="col-12 justify-content-between" onclick="loadCourse(<?php echo $course['id']; ?>)">
                                            <h6 class="mb-1"><?php echo $course['name']; ?></h6>
                                        </div>
                                        <div class="col-8 justify-content-between mb-1">
                                            <small class="mb-1"> <?php echo $course['code']; ?></small>
                                        </div>
                                        <!-- <div class="col-4 d-flex justify-content-end align-self-end">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <!--<button type="button" class="btn">i</button>
                                                <button type="button" class="btn btn-secondary" onclick="moveGroup(<?php echo $group_id; ?>);" data-group-id="<?php echo $group_id; ?>"><i class="fa fa-heart" aria-hidden="true"></i></button>
                                            </div> 
                                        </div> -->
                                    </div>
                                </div>
                                <?php  }  //closing the loop ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- second page -->
                <div class="col-md-12 col-lg-8 p-3 animated page2" style="display:none;">
                    <!-- Subject Detail row -->
                    <div class="row" >
                        
                        <div class="container-fluid">
                            <div class="col-12">
                                <button type="button" class="btn btn-info mb-3 d-none d-md-block d-lg-none" onclick="goBackAnimation()"> <i class="fa fa-angle-left fa-2x"></i> </i> </button>
                                <h2 id="course_name"></h2>
                                <div class="row">
                                    <div class="col-4 align-items-start">
                                        <h6> Course code: </h6>
                                    </div>
                                    <div class="col-6 align-items-end">
                                        <h6 id="course_code"></h6>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-4 align-items-start">
                                        <h6> Credit:</h6>
                                    </div>
                                    <div class="col-8 align-items-end">
                                        <h6 id="course_credits"></h6>
                                    </div>
                                </div>
                                  <div class="row">
                                    <div class="col-4 align-items-start">
                                        <h6> Hours per week: </h6>
                                    </div>
                                    <div class="col-6 align-items-start">
                                        <h6 id="course_hours"></h6>
                                    </div>
                                </div>
                                <h6 class="mt-5"> LEARNING OUTCOMES </h6>
                                <p id="course_learning_outcomes"></p>
                                <h6 class="mt-5"> OVERVIEW</h6>
                                <p id="course_overview"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
    
    <?php include('includes/footer.php'); ?>
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript">
        function loadCourse(id){
            //we do the ajax request for the course information
            $.ajax({
                url: '/ajax/course.ajax.php',
                method: 'post',
                dataType: 'json',
                data: {id: id },
                beforeSend: function() {
                    // fadeout page 2
                    $('.page2').hide()
                },
                success: function(response) {
                    msgHandler(response.success, response.msg);
                    //lets modifiy the information on the actual page
                    
                    $('#course_name').html(response.info.name);
                    $('#course_code').html(response.info.code);
                    $('#course_credits').html(response.info.credits);
                    $('#course_hours').html(response.info.hours_per_week);
                    $('#course_learning_outcomes').html(response.info.learning_outcomes);
                    $('#course_overview').html(response.info.overview);
                    
                },
                complete: function() {
                    openDetailAnimation();
                }
            });
        }
    </script>
</html>