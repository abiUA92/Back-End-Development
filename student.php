<!-- all code is my own unless otherwise stated -->
<html>
    <?php 
    // imports navigation bar and functions from separate files 
        include('navBar.php'); 
        include('functions.php');
    ?>

    <h1>Student</h1>

    <!-- separate forms in to coloured boxes in one column with the table in separate column -->
    <div class="row">
        <div class="column-right">  
            <div class="formData" style="background-color: blue;">

                <!-- form for entering a new pupil record -->
                <h2>Enter new pupil record</h2>
                
                <form method="post" action="student.php">
                    <label for="fname">First name:</label><br>
                    <input type="text" id="fname" name="fname" required><br>
                    
                    <label for="lname">Last name:</label><br>
                    <input type="text" id="lname" name="lname" required><br>
                    
                    <label for="family">Family ID:</label><br>
                    <input type="text" id="family" name="family" required><br>

                    <?php
                    // used to populate a selection box with valid class ids
                        function classSelection($type){
                            // connect to database
                                $database = mysqli_connect("localhost", "root","","rishton-academy");
                                $sql = "SELECT CLASS_ID, CLASS_NAME FROM class";
                                $result = mysqli_query($database,$sql);

                                // stores data in array
                                $options = array();
                                while ($row = mysqli_fetch_array($result)) {
                                    $options[$row['CLASS_ID']] = $row['CLASS_NAME'];
                                }

                                // create selection box
                                // type = updateClass or (add) class
                                echo '<label for="'.$type.'">Class:</label><br>
                                <select name="'.$type.'">';

                                // create place holder option
                                echo '<option value="">Select an option</option>';
                                // populate box with options
                                // each label value = class ID
                                foreach ($options as $id => $label) {
                                    echo '<option value="' . $id . '">' . $label . '</option>';
                                }
                            ?>
                            </select><br>
                        <?php 
                            } 
                            
                            $type = "class";
                            classSelection($type);
                        ?>
                        <br>
                    
                    <label for="DOB">Date of birth:</label><br>
                    <input type="date" id="DOB" name="DOB" style="width: 177px" required><br>
                    
                    <label for="medical">Medical Information:</label><br>
                    <textarea type="text" id="medical" name="medical"></textarea><br> 
                    
                    <input type="submit" name="submit"> <br>                        
                </form>
                
            </div>
            <div class="formData" style="background-color: #d4c32d; color:black;">

                <!-- form for updating a pupil record -->
                <h2>Update a pupil's record</h2>
                <!-- search for the pupil id. students details will fill the rest of the form if the record exists -->
                <form method="post" action="student.php" style="color:black;">
                    <label for="pupilID">Enter Pupil ID:</label><br>
                    <input type="text" id="pupilID" name="pupilID"><br>
                    <input type="submit" name="pupilID-submit" id="pupilID-submit"> <br>   

                <!-- disabled until a record has been searched for, then the user can change the record and update the database -->
                    <label for="update-fname">First name:</label><br>
                    <input type="text" id="update-fname" name="update-fname" disabled ><br>

                    <label for="update-lname">Last name:</label><br>
                    <input type="text" id="update-lname" name="update-lname" disabled><br>
                    
                    <label for="update-family">Family ID:</label><br>
                    <input type="text" id="update-family" name="update-family" disabled><br>
                    
                    <?php
                        $type = "update-class";
                        classSelection($type);
                    ?>
                    
                    <label for="update-DOB">Date of birth:</label><br>
                    <input type="date" id="update-DOB" name="update-DOB" style="width: 177px" disabled><br>
                    
                    <label for="update-medical">Medical Information:</label><br>
                    <textarea type="text" id="update-medical" name="update-medical" disabled></textarea><br> 
                    
                    <!-- stores pupilID after form has been submitted -->
                    <input type="hidden" id="savePupilID" name="savePupilID"><br>
                    
                    <input type="submit" name="update-submit" id="update-submit" disabled> <br>                        
                </form>
                
            </div>

            <div class="formData" style="background-color: pink; color:black;">
            <!-- form for deleting a record -->
                <h2>Delete a student record</h2>
                <!-- user searches for pupilID and then the record is displayed in a table,
                 allows user to ensure they have selected the correct record -->
                <form method="post" action="student.php" style="color:black;">
                        <label for="deletePupilID">Enter Pupil ID:</label><br>
                        <input type="text" id="deletePupilID" name="deletePupilID"><br>
                        <input type="submit" name="deletePupilID-submit" id="deletePupilID-submit"> <br> 
                </form>                                

            </div> 
                <!-- allow user to find family id here instead of navigating to family page -->
            <div class="formData" style="background-color: orange; color:black;">
                <h3>Search for family ID using email address</h3>
                <form method="post" action="student.php" style="color:black;">
                    <label for="searchFamilyID">Enter email address:</label><br>
                    <input type="text" id="searchFamilyID" name="searchFamilyID"><br>
                    <input type="submit" name="searchFamilyID-submit" id="searchFamilyID-submit"> <br> 
                </form> 
            </div>
        </div>
        <!-- <div class="row"> -->
        <div class="column-left">
            <div class="formData">
                <!-- displays the table from database when page loads -->
                <h2>View student</h2>

                <h3 style="color:red;">The Family ID is required to add parents and parents to the database.<br> 
                Use the orange search box below to search for ID using <br> an email address.<h3>
                
                <form method="post" action="student.php">
                    <input type="submit" name="orderByClass" id="orderByClass" value="order by class">
                    <input type="submit" name="orderByFamily" id="orderByFamily" value="order by family">
                </form>


                
                <table border="1" cellspacing="4" cellpadding="4">
                    <thead>
                        <tr>     
                            <th>PUPIL_ID</th>
                            <th>CLASS_ID</th>
                            <th>FAMILY_ID</th>
                            <th>PUPIL_FNAME</th>
                            <th>PUPIL_SNAME</th>
                            <th>PUPIL_MEDICAL</th>
                            <th>PUPIL_DOB</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
            
                <!-- call function to output records in to table -->
                <?php displayTable("PUPIL_ID ASC"); ?>            
    

                <?php
                    function displayTable($order){
                        // fetches table from database

                        // $order = "CLASS_ID DESC";
                        $database = mysqli_connect("localhost", "root","","rishton-academy");
                        $sql = "SELECT * FROM pupil ORDER BY $order";
                        $result = mysqli_query($database,$sql);
                        // $row=mysqli_fetch_array($result);

                        // Check the connection to the database
                        if (!$database) {
                            die("Connection failed: ");
                        }

                        // Clear the previous table data
                        echo '<script>document.getElementById("table-body").innerHTML = "";</script>';
    
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo '<tr>
                                    <td>'.$row["PUPIL_ID"].'</td>
                                    <td>'.$row["CLASS_ID"].'</td>
                                    <td>'.$row["FAMILY_ID"].'</td>
                                    <td>'.$row["PUPIL_FNAME"].'</td> 
                                    <td>'.$row["PUPIL_SNAME"].'</td>
                                    <td>'.$row["PUPIL_MEDICAL"].'</td>
                                    <td>'.$row["PUPIL_DOB"].'</td>
                                </tr>';  
                        }
                        // echo '</table>';
                        
                        mysqli_close($database);
                    };
                ?>
            </div>
        </div>
        <!-- </div>  -->

    </div> 
</html>

<?php

    // -----------------------------search for id with email address----------------------------------------------
    
    $database = mysqli_connect("localhost", "root","","rishton-academy");
    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if (isset($_POST['searchFamilyID-submit'])) {
        // search for record with family ID
        $searchFamilyID = mysqli_real_escape_string($database, $_POST['searchFamilyID']);
        searchFamilyID($searchFamilyID);
        mysqli_close($database);
    };

    //---------------------------------------------add new student record--------------------------------------------------------------------------------------
    $database = mysqli_connect("localhost", "root","","rishton-academy");
    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }
        
    if (isset($_POST['submit'])) {
        // get data from html input boxes
        // prevent special characters from causing issues when inputting into database
            $fname = mysqli_real_escape_string($database, $_POST['fname']);
            $lname = mysqli_real_escape_string($database, $_POST['lname']);
            $family = mysqli_real_escape_string($database, $_POST['family']);
            $class = mysqli_real_escape_string($database, $_POST['class']);
            $DOB = mysqli_real_escape_string($database, $_POST['DOB']);
            $medical = mysqli_real_escape_string($database, $_POST['medical']);

            // ensure that the familyID entered exists
            $found = checkFamilyID($family);
            
            if (!$found) {
                echo "Invalid Family ID";
                exit;
            }

            // validate and sanitise user input
            validation($fname,$lname,$family,$class,$DOB,"00000000000");

            // prepare statements to insert new record in to table
            $stmt = mysqli_prepare($database, "INSERT INTO pupil (CLASS_ID,FAMILY_ID,PUPIL_FNAME,PUPIL_SNAME,PUPIL_DOB,PUPIL_MEDICAL) VALUES (?,?,?,?,?,?)" );
            mysqli_stmt_bind_param($stmt, "iissss", $class,$family,$fname,$lname,$DOB,$medical);
            $result = mysqli_stmt_execute($stmt);
               
            if ($result) {
                echo "Record added successfully.";
            } else {
                echo "Error adding record.";
            }
            // clears old table and outputs table again with new record
            displayTable("PUPIL_ID ASC");
        }
    // close database to preserve resources
    mysqli_close($database);

   

// ----------------------------------------------------------------update record -----------------------------

    // establish database connection
    $database = mysqli_connect("localhost", "root","","rishton-academy");

    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // search for pupilID to update record
    if (isset($_POST['pupilID-submit'])) {
        $pupilID = mysqli_real_escape_string($database, $_POST['pupilID']);

        $stmt = mysqli_prepare($database, "SELECT * FROM pupil WHERE PUPIL_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $pupilID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // If the primary key is found, fill in the text boxes with the data
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $updateFname = htmlspecialchars($row['PUPIL_FNAME']);
            $updateSname = htmlspecialchars($row['PUPIL_SNAME']);
            $updateMedical = htmlspecialchars($row['PUPIL_MEDICAL']);
            $updateDOB = htmlspecialchars($row['PUPIL_DOB']);
            $updateFamily = htmlspecialchars($row['FAMILY_ID']);
            $updateClass = htmlspecialchars($row['CLASS_ID']);



            // add the record data into the input fields in the update form
            // enable the fields on the update record form so user can edit data
            ?>
            <script>
                // put record values in to form
                document.getElementById('update-fname').value = '<?= $updateFname ?>';
                document.getElementById('update-lname').value = '<?= $updateSname ?>';
                document.getElementById('update-family').value = '<?= $updateFamily ?>';
                document.getElementById('update-DOB').value = '<?= $updateDOB ?>';
                document.getElementById('update-medical').value = '<?= $updateMedical ?>';
                document.getElementById('savePupilID').value = '<?= $pupilID ?>';

                // enable the form inputs so the user can edit
                document.getElementById('update-fname').disabled = false;
                document.getElementById('update-lname').disabled = false;
                document.getElementById('update-family').disabled = false;
                document.getElementById('update-DOB').disabled = false;
                document.getElementById('update-medical').disabled = false;
                document.getElementById('update-submit').disabled = false;

                // stores pupilID for later use
                document.getElementById('pupilID').hidden = true;
                document.getElementById('pupilID-submit').disabled = true;
                document.getElementById('pupilID-submit').hidden = true;
            </script>
            <?php
        }
    }    
    
    if (isset($_POST['update-submit'])) {
        // store user inputs in variables
        $pupilID =mysqli_real_escape_string($database, $_POST['savePupilID']);
        $fname = mysqli_real_escape_string($database, $_POST['update-fname']);
        $lname = mysqli_real_escape_string($database, $_POST['update-lname']);
        $family = mysqli_real_escape_string($database, $_POST['update-family']);
        $class = mysqli_real_escape_string($database, $_POST['update-class']);
        $DOB = mysqli_real_escape_string($database, $_POST['update-DOB']);
        $medical = mysqli_real_escape_string($database, $_POST['update-medical']);


        // perform validation and sanitization for user input
        validation($fname,$lname,$family,$class,$DOB,"00000000000");

                    
        $found = checkFamilyID($family);
            
        if (!$found) {
            echo "Invalid Family ID";
            exit;
        }
        

        // update the record in the database
        $stmt = mysqli_prepare($database, "UPDATE pupil SET PUPIL_FNAME=?, PUPIL_SNAME=?, PUPIL_MEDICAL=?, PUPIL_DOB=?, FAMILY_ID=?, CLASS_ID=? WHERE PUPIL_ID=?");
        mysqli_stmt_bind_param($stmt, "ssssiii", $fname, $lname, $medical, $DOB, $family, $class, $pupilID);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {
            echo "Record updated successfully.";
        } else {
            echo "Error updating record.";
        }
        displayTable("PUPIL_ID ASC");
    }

    // close the database connection
    mysqli_close($database);

    //--------------------------------------------------delete record----------------------------------------------------------------
    $database = mysqli_connect("localhost", "root","","rishton-academy");
    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }

    if (isset($_POST['deletePupilID-submit'])) {
        // search for record with pupil ID
        $deletePupilID = mysqli_real_escape_string($database, $_POST['deletePupilID']);
        
        // search table for record matching pupil ID
        $stmt = mysqli_prepare($database, "SELECT * FROM pupil WHERE PUPIL_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $deletePupilID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // display single record to user if the record is found
        if (mysqli_num_rows($result) > 0) {
        ?>
            <div class="formData">
                <form method="post">
                    <table border="1" cellspacing="4" cellpadding="4" style="border-color:pink;">
                        <tr>
                            <td>PUPIL_ID</td>
                            <td>CLASS_ID</td>
                            <td>FAMILY_ID</td>
                            <td>PUPIL_FNAME</td>
                            <td>PUPIL_SNAME</td>
                            <td>PUPIL_MEDICAL</td>
                            <td>PUPIL_DOB</td>
                        </tr>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row["PUPIL_ID"]; ?></td>
                                <td><?php echo $row["CLASS_ID"]; ?></td>
                                <td><?php echo $row["FAMILY_ID"]; ?></td>
                                <td><?php echo $row["PUPIL_FNAME"]; ?></td>
                                <td><?php echo $row["PUPIL_SNAME"]; ?></td>
                                <td><?php echo $row["PUPIL_MEDICAL"]; ?></td>
                                <td><?php echo $row["PUPIL_DOB"]; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                    <!-- ensure user selected the correct record -->
                    <p>Are you sure you want to delete this record?</p>
                    <form method="post">
                        <input type="hidden" name="deletePupilID" value="<?php echo $deletePupilID; ?>">
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
        // get pupilID from  hidden html input
        $deletePupilID = mysqli_real_escape_string($database, $_POST['deletePupilID']);
    
        // prepare sql statements
        $stmt = mysqli_prepare($database, "DELETE FROM pupil WHERE PUPIL_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $deletePupilID);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {            
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record.";
        };
        displayTable("PUPIL_ID ASC");

    };
    
    mysqli_close($database);


    // buttons above table allow user to change order of records
    //  based on class or family IDs
    if (isset($_POST['orderByClass'])) {
        displayTable("CLASS_ID ASC");
    };
    if (isset($_POST['orderByFamily'])) {
        displayTable("FAMILY_ID ASC");
    };

?>
