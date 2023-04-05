<!-- all code is my own unless otherwise stated -->
<!-- for more detailed comments for common functions see student.php -->
<html>
    <?php 
        include('navBar.php');
        include('functions.php');


    // connect to database
    $database = mysqli_connect("localhost", "root","","rishton-academy");
    $sql = "SELECT CLASS_ID, CLASS_NAME FROM class";
    $result = mysqli_query($database,$sql);

    // Check the connection to the database
    if (!$database) {
        die("Connection failed: ");
    }
    ?>
    
    <h2>Class Registers<h2>
    <form method="post" action="registers.php" id="form-body">

    <?php
    // populate form with a button for every class in database
        while ($row = mysqli_fetch_assoc($result)) {
            echo'    
                <input type="submit" name='.$row["CLASS_ID"].' value="'.$row["CLASS_NAME"].'" class="register" "> ';


            // if a button is pressed
            if(isset($_POST[$row["CLASS_ID"]])){
                $classID = $row["CLASS_ID"];
                // get pupil id and names from table
                $stmt = mysqli_prepare($database, "SELECT PUPIL_ID, PUPIL_FNAME, PUPIL_SNAME FROM pupil WHERE CLASS_ID = ? ORDER BY PUPIL_SNAME ASC");
                mysqli_stmt_bind_param($stmt, "i", $classID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
        
                // if records with class id are found
                if (mysqli_num_rows($result) > 0) {
                    // clear page of other buttons
                    echo '<script>document.getElementById("form-body").innerHTML = "";</script>';

                ?>
                <!-- output list of student records to user -->
                    <div class="formData">
                        <table border="1" cellspacing="4" cellpadding="4" style="border-color:white;" name="attendance">
                            <tr>
                                <td>PUPIL_ID</td>
                                <td>PUPIL_FNAME</td>
                                <td>PUPIL_SNAME</td>
                                <td>PRESENT</td>
                            </tr>
                            <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                                <tr>
                                    <td><?php echo $row["PUPIL_ID"]; ?></td>
                                    <td><?php echo $row["PUPIL_FNAME"]; ?></td>
                                    <td><?php echo $row["PUPIL_SNAME"]; ?></td>
                                    <td><input type="checkbox" name="ckb.$row['PUPIL_ID']" style="width:50px;height:50px;" class="checkbox"></td>
                                </tr>
                            <?php } ?>
                            <tr>
                                <td>Select all</td>
                                <td><input type="checkbox" id ="selectAll" name="selectAll" style="width:50px;height:50px;"></td>
                                <td>Submit Attendance</td>
                                <!-- disabled as attendance function is not yet complete -->
                                <td><input type="submit" name="submitAttendance" class="register" disabled> </td>                             
                            </tr>
                        </table>
                        <!-- allow user to go back to choose another class -->
                        <button type="button" onclick="history.back()">Back</button>
                    </div>
            <?php
                } else {
                    echo "<br> Pupil record/s not found";
                }    
            }
        };




    // if(isset($_POST['submitAttendance'])){
    //     // Initialize counter variable
    //     $selectedCount = 0;
    //     // echo $result;
        
    //     // Loop through checkboxes
    //     while ($row = mysqli_fetch_assoc($result)) {
    //         $checkboxName = "ckb".$row['PUPIL_ID'];
    //         echo $checkboxName;
    //         if(isset($_POST[$checkboxName]) && $_POST[$checkboxName] == 'on') {
    //             $selectedCount++;
    //         }
    //     }
        
    //     // Print the count
    //     echo "Number of checkboxes selected: " . $selectedCount;

    //     if (mysqli_error($database)) {
    //         die("SQL error: " . mysqli_error($database));
    //     }
    // }
        ?>
        </form>
</html>

    <?php    

        if(isset($_POST['submitAttendance'])){

            // number of students in register
        $length = $_COOKIE["length"];
        echo $length;
        };
    ?>
<script>
    window.onload=function(){
        let selectAll = document.getElementById("selectAll");

        var elements = document.getElementsByClassName("checkbox")

        // store the amount of checkboxes (and therefore number of students) in a cookie
        document.cookie = "length=" + elements.length;

        selectAll.addEventListener("change",function(){

            for(i=0;i<elements.length;i++){
                // checks or unchecks all checkboxes
                elements[i].checked=selectAll.checked;
            }
        $result = mysqli_stmt_get_result($stmt);
        })
    }
        // if(isset($_POST['submitAttendance'])){
        //     // $sql="SELECT PUPIL_ID FROM pupil WHERE CLASS_ID = $classID";
        //     // $result = mysqli_query($database, $sql);
            
        //     // $count = 0;
        //     // while ($row = mysqli_fetch_assoc($result)){
        //     //     $count ++;
        //     // };
        //     echo 'elements.length';
        // };
</script>