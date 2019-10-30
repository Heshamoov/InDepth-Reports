
<!--Initializing NavBar-->

<div class="w3-bar  w3-blue-gray w3-card  w3-medium" >


    <l style="vertical-align: middle; padding-left: 10px; font-size: 30px; color: whitesmoke"> REPORTS CENTER</l>
    
    
<!--    <div style="float: right; background-color: #607D8B; color: white"class="w3-responsive w3-dropdown-hover w3-hide-small">
        <button class="w3-button" title="Advanced Reports">More <i class="fa fa-caret-down"></i></button>     
        <div class="w3-dropdown-content w3-card-4 w3-bar-block" >

            <form name="frm" action="gender_wise.php" method="POST">

                <input class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-mobile" value="Gender Wise" type="submit">
            </form>   

            <form name="frm" action="teacher_wise.php" method="POST">
                        <input id="iurl" type="hidden" value="<?php echo $iurl ?>" name="iurl"/>
                <input id="token" type="hidden" value="<?php echo $token ?>" name="token"/> 
                <input class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-mobile" value="Teacher Wise" type="submit">
            </form>   
            <a href="#" class="w3-bar-item w3-button w3-mobile">....</a>
        </div>
    </div>-->

<!--    <form name="frm" style="float: right" action="student_wise.php" method="POST">
        <input class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-mobile" value="Student Wise" type="submit">
    </form>   -->
<!--    <form name="frm" style="float: right" action="batch_wise.php" method="POST">
        <input class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-mobile" value="Batch Wise" type="submit">
    </form>   -->
    <form action="logout.php" style="float: right;padding-top: 5px; padding-right: 10px;" >
        <button  type ='submit' href='logout.php' class="btn btn-danger btn-sm">
            <span class="glyphicon glyphicon-log-out"></span> Log out
        </button>
    </form>




    <form name="frm" style="float: right" action="advanced.php" method="POST">
        <input class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-mobile" id="navAdvanced" value="Advanced Analysis" type="submit">
    </form>   

    <form name="frm" style="float: right" action="attainment.php" method="POST">
        <input class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-mobile" id="navAttainment" value="Attainment Analysis" type="submit">
    </form>   

    <form name="frm" style="float: right" action="subject_wise.php" method="POST">
        <input class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-mobile" id="navSubjectWise" value="Subject Wise" type="submit">
    </form>  

    <form name="frm" style="float: right" action="statistics.php" method="POST">
        <input class="w3-bar-item w3-button w3-hide-small w3-hover-white w3-mobile" id="navStatistics" value="Statistics" type="submit">
    </form>   
    <!--     <form name="frm" style="float: right"action="import.php" method="POST">
                    <input class="w3-bar-item w3-button w3-hide-small  w3-right-align w3-hover-white w3-mobile" value="Home" type="submit">
                </form>  -->

</div>