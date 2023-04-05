<!-- all code is my own unless otherwise stated -->
<!-- for more detailed comments for common functions see student.php -->
<html>
    <?php 
        include('navBar.php'); 
        include('functions.php');
    ?>


    <h1>Teacher</h1>
    <div class="row">
        <div class="column-right">
            <div class="formData" style="background-color: blue;">
                <!-- form for entering a new teacher record -->
                    <h2>Enter new teacher record</h2>
                    
                    <form method="post" action="teacher.php">
                        <label for="fname">First name:</label><br>
                        <input type="text" id="fname" name="fname"><br>
                        
                        <label for="lname">Last name:</label><br>
                        <input type="text" id="lname" name="lname"><br>
                        
                        <label for="address">Address:</label><br>
                        <input type="text" id="address" name="address"><br>
                        
                        <label for="phone">Phone Number:</label><br>
                        <input type="tel" id="phone" name="phone"><br>                    
                        
                        
                        <?php
                        function salarySelection($type){
                                $database = mysqli_connect("localhost", "root","","rishton-academy");
                                $sql = "SELECT SALARY_ID, SPINE_POINT, PAY FROM salary";
                                $result = mysqli_query($database,$sql);

                                $options = array();
                                while ($row = mysqli_fetch_array($result)) {
                                    $options[$row['SALARY_ID']] = $row['SPINE_POINT'] . ' - ' . $row['PAY'];
                                }
                            ?>
      
                            <?php

                                echo '<label for="'.$type.'">Salary:</label><br>
                                <select name="'.$type.'">';

                                echo '<option value="">Select an option</option>';
                                foreach ($options as $id => $label) {
                                    echo '<option value="' . $id . '">' . $label . '</option>';
                                }
                            ?>
                            </select><br>
                        <?php 
                        } 
                        
                        $type = "salary";
                        salarySelection($type);
                        ?>
                        <br>
                        
                        <label for="bgCheck">Background Check Passed:</label><br>
                        <input type="checkbox" id="bgCheck" name="bgCheck"><br>                    
                        
                        <input type="submit" name="submit"> <br>                        
                    </form>                        
            </div>
            <div class="formData" style="background-color: #d4c32d; color:black;">

                <!-- form for updating a teacher record -->
                <h2>Update a teacher's record</h2>
                <!-- search for the teacher id. teachers details will fill the rest of the form if the record exists -->
                <form method="post" action="teacher.php" style="color:black;">
                    <label for="teacherID">Enter Teacher ID:</label><br>
                    <input type="text" id="teacherID" name="teacherID"><br>
                    <input type="submit" name="teacherID-submit" id="teacherID-submit"> <br>   

                <!-- disabled until a record has been searched for, then the user can change the record and update the database -->
                    <label for="update-fname">First name:</label><br>
                    <input type="text" id="update-fname" name="update-fname" disabled><br>
                    
                    <label for="update-lname">Last name:</label><br>
                    <input type="text" id="update-lname" name="update-lname" disabled><br>
                    
                    <label for="update-address">Address:</label><br>
                    <input type="text" id="update-address" name="update-address" disabled><br>
                    
                    <label for="update-phone">Phone Number:</label><br>
                    <input type="text" id="update-phone" name="update-phone" disabled><br>                    
                    
                    <?php
                        $type = "update-salary";
                        salarySelection($type);
                    ?>
                    
                    <label for="update-bgCheck">Background Check Passed:</label><br>
                    <input type="checkbox" id="update-bgCheck" name="update-bgCheck" disabled><br> 
                    
                    <!-- stores ID after form has been submitted -->
                    <input type="hidden" id="saveID" name="saveID"><br>
                    
                    <input type="submit" name="update-submit" id="update-submit" disabled> <br>                        
                </form>
                
            </div>

            <div class="formData" style="background-color: pink; color:black;">
            <!-- form for deleting a record -->
                <h2>Delete a teacher record</h2>
                <!-- user searches for teacherID and then the record is displayed in a table, allows user to ensure they have selected the correct record -->
                <form method="post" action="teacher.php" style="color:black;">
                        <label for="deleteTeacherID">Enter Teacher ID:</label><br>
                        <input type="text" id="deleteTeacherID" name="deleteTeacherID"><br>
                        <input type="submit" name="deleteTeacherID-submit" id="deleteTeacherID-submit"> <br> 
                </form>  
            </div>                              
        </div>
            <div class="column-left">
                <div class="formData">
                    <!-- displays the table from database when page loads -->
                    <h2>View teachers</h2>

                    <table border="1" cellspacing="4" cellpadding="4">
                        <thead>
                            <tr>     
                                <th>TEACH_ID</th>
                                <th>TEACH_FNAME</th>
                                <th>TEACH_SNAME</th>
                                <th>TEACH_ADDRESS</th>
                                <th>TEACH_PHONE</th>
                                <th>TEACH_SALARY</th>
                                <th>TEACH_BG_CHECK</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            
                    <?php displayTable(); ?>

                    <?php
                        function displayTable(){
                            // fetches table from database

                            $database = mysqli_connect("localhost", "root","","rishton-academy");
                            $sql = "SELECT * FROM teachers";
                            $result = mysqli_query($database,$sql);

                            // Check the connection to the database
                            if (!$database) {
                                die("Connection failed: ");
                            }

                            // Clear the previous table data
                            echo '<script>document.getElementById("table-body").innerHTML = "";</script>';

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>
                                        <td>'.$row["TEACH_ID"].'</td>
                                        <td>'.$row["TEACH_FNAME"].'</td>
                                        <td>'.$row["TEACH_SNAME"].'</td>
                                        <td>'.$row["TEACH_ADDRESS"].'</td>
                                        <td>'.$row["TEACH_PHONE"].'</td>
                                        <td>'.$row["TEACH_SALARY"].'</td>
                                        <td>'.$row["TEACH_BG_CHECK"].'</td>
                                    </tr>';  
                            }
                            
                            mysqli_close($database);
                        };

                    ?>
            </div>

        </div>
    </div>
</html>

<?php


    //---------------------------------------------add new teacher record--------------------------------------------------------------------------------------
    $database = mysqli_connect("localhost", "root","","rishton-academy");
    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }
        
    if (isset($_POST['submit'])) {

            $fname = mysqli_real_escape_string($database, $_POST['fname']);
            $sname = mysqli_real_escape_string($database, $_POST['lname']);
            $address = mysqli_real_escape_string($database, $_POST['address']);
            $phone = mysqli_real_escape_string($database, $_POST['phone']);
            $salary = mysqli_real_escape_string($database, $_POST['salary']);
            $bgCheck = mysqli_real_escape_string($database, $_POST['bgCheck']);

            if(isset($_POST['bgCheck'])){
                $bgCheck = 1;
            }else{
                $bgCheck = 0;
            };

            teacherValidation($fname, $sname,$address,$phone,$salary);

            $stmt = mysqli_prepare($database, "INSERT INTO teachers (TEACH_FNAME,TEACH_SNAME,TEACH_ADDRESS,TEACH_PHONE,TEACH_SALARY,TEACH_BG_CHECK) VALUES (?,?,?,?,?,?)" );
            mysqli_stmt_bind_param($stmt, "ssssii", $fname,$sname,$address,$phone,$salary,$bgCheck);
            $result = mysqli_stmt_execute($stmt);
               
            if ($result) {
                echo "Record added successfully.";
            } else {
                echo "Error adding record.";
            }
            displayTable();

        }
    mysqli_close($database);



    //--------------------------------------------------delete record----------------------------------------------------------------
    $database = mysqli_connect("localhost", "root","","rishton-academy");
    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }


    if (isset($_POST['deleteTeacherID-submit'])) {
        // search for record with teacher ID
        $deleteTeacherID = mysqli_real_escape_string($database, $_POST['deleteTeacherID']);
    
        $stmt = mysqli_prepare($database, "SELECT * FROM teachers WHERE TEACH_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $deleteTeacherID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // display single record to user if the record is found
        if (mysqli_num_rows($result) > 0) {
        ?>
            <div class="formData">
                <form method="post">
                    <table border="1" cellspacing="4" cellpadding="4" style="border-color:pink;">
                        <thead>
                            <tr>     
                                <th>TEACH_ID</th>
                                <th>TEACH_FNAME</th>
                                <th>TEACH_SNAME</th>
                                <th>TEACH_ADDRESS</th>
                                <th>TEACH_PHONE</th>
                                <th>TEACH_SALARY</th>
                                <th>TEACH_BG_CHECK</th>
                            </tr>

                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row["TEACH_ID"]; ?></td>
                                <td><?php echo $row["TEACH_FNAME"]; ?></td>
                                <td><?php echo $row["TEACH_SNAME"]; ?></td>
                                <td><?php echo $row["TEACH_ADDRESS"]; ?></td>
                                <td><?php echo $row["TEACH_PHONE"]; ?></td>
                                <td><?php echo $row["TEACH_SALARY"]; ?></td>
                                <td><?php echo $row["TEACH_BG_CHECK"]; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                    <!-- ensure user selected the correct record -->
                    <p>Are you sure you want to delete this record?</p>
                    <form method="post">
                        <input type="hidden" name="deleteTeacherID" value="<?php echo $deleteTeacherID; ?>">
                        <input type="submit" name="delete-submit" id="delete-submit" value="Yes">
                        <button type="button" onclick="history.back()">No</button>
                    </form>
                </form>
            </div>
        <?php
            } else {
                echo "Record not found";
            }
        ?>

<?php
    };
    // delete record from table
    if (isset($_POST['delete-submit'])) {
        $deleteTeacherID = mysqli_real_escape_string($database, $_POST['deleteTeacherID']);
    
        // check that the teacherID is not being used as a foreign key in class table
        $sql = "SELECT TEACH_ID FROM class WHERE TEACH_ID = '$deleteTeacherID'";
        $result = mysqli_query($database,$sql);

        // only delete if a link is not found
        if (($row = $result->fetch_assoc())==null){

            $stmt = mysqli_prepare($database, "DELETE FROM teachers WHERE TEACH_ID = ?");
            mysqli_stmt_bind_param($stmt, "i", $deleteTeacherID);
            $result = mysqli_stmt_execute($stmt);
    
            if ($result) {            
                echo "Record deleted successfully";
            } else {
                echo "Error deleting record.";
            };
            displayTable();
        }else{
            echo "This teacher ID is linked to a class. Replace the teacher in the class table before deletion.";
        };

    };
    
    mysqli_close($database);


// -----------------------update records---------------------------------------

    // establish database connection
    $database = mysqli_connect("localhost", "root","","rishton-academy");

    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // search for teacherID to update record
    if (isset($_POST['teacherID-submit'])) {
        $teacherID = mysqli_real_escape_string($database, $_POST['teacherID']);
        // echo htmlspecialchars($teacherID);

        $stmt = mysqli_prepare($database, "SELECT * FROM teachers WHERE TEACH_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $teacherID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result) {
            echo "Record found.";
        } else {
            echo "Error.";
        }

        // If the primary key is found, fill in the text boxes with the data
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $updateFname = htmlspecialchars($row['TEACH_FNAME']);
            $updateSname = htmlspecialchars($row['TEACH_SNAME']);
            $updateAddress = htmlspecialchars($row['TEACH_ADDRESS']);
            $updatePhone = htmlspecialchars($row['TEACH_PHONE']);
            $updateSalary = htmlspecialchars($row['TEACH_SALARY']);
            $updateBgCheck = htmlspecialchars($row['TEACH_BG_CHECK']);


            // add the record data into the input fields in the update form
            // enable the fields on the update record form so user can edit data
            ?>
            <script>
                document.getElementById('update-fname').disabled = false;
                document.getElementById('update-lname').disabled = false;
                document.getElementById('update-address').disabled = false;
                document.getElementById('update-phone').disabled = false;
                document.getElementById('update-bgCheck').disabled = false;

                document.getElementById('update-fname').value = '<?= $updateFname ?>';
                document.getElementById('update-lname').value = '<?= $updateSname ?>';
                document.getElementById('update-address').value = '<?= $updateAddress ?>';
                document.getElementById('update-phone').value = '<?= $updatePhone ?>';

                document.getElementById('saveID').value = '<?= $teacherID ?>';


                document.getElementById('update-submit').disabled = false;

                document.getElementById('teacherID').hidden = true;
                document.getElementById('teacherID-submit').disabled = true;
                document.getElementById('teacherID-submit').hidden = true;
            </script>
            <?php
        }


    }
    
    
    if (isset($_POST['update-submit'])) {
        $teacherID =mysqli_real_escape_string($database, $_POST['saveID']);
        $fname = mysqli_real_escape_string($database, $_POST['update-fname']);
        $lname = mysqli_real_escape_string($database, $_POST['update-lname']);
        $address = mysqli_real_escape_string($database, $_POST['update-address']);
        $phone = mysqli_real_escape_string($database, $_POST['update-phone']);
        $salary = mysqli_real_escape_string($database, $_POST['update-salary']);

        if(mysqli_real_escape_string($database, $_POST['update-bgCheck']) == ""){
            $bgCheck = 0;
        }else{
            $bgCheck = 1;
        };

        
        // perform validation and sanitization for user input
        teacherValidation($fname, $lname,$address,$phone,$salary);


        // update the record in the database
        $stmt = mysqli_prepare($database, "UPDATE teachers SET TEACH_FNAME=?, TEACH_SNAME=?, TEACH_ADDRESS=?, TEACH_PHONE=?, TEACH_SALARY=?, TEACH_BG_CHECK=? WHERE TEACH_ID=?");
        mysqli_stmt_bind_param($stmt, "ssssiii", $fname, $lname, $address, $phone, $salary, $bgCheck, $teacherID);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo "Record updated successfully.";
        } else {
            echo "Error updating record.";
        }
        displayTable();
    }

    // close the database connection
    mysqli_close($database);



?>


