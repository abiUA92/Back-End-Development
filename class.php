<!-- all code is my own unless otherwise stated -->
<!-- for more detailed comments for common functions see student.php -->
<html>
    <?php include('navBar.php'); ?>

    <h1>Class</h1>
    <div class="row">
        <div class="column-right">
            <div class="formData" style="background-color: blue;">
                <!-- form for entering a new class record -->
                    <h2>Enter new class record</h2>
                    
                    <form method="post" action="class.php">
                        <label for="name">Class name:</label><br>
                        <input type="text" id="name" name="name"><br>

            
                        <?php
                        function selection($type){
                                $database = mysqli_connect("localhost", "root","","rishton-academy");
                                $sql = "SELECT TEACH_ID, TEACH_SNAME FROM teachers";
                                $result = mysqli_query($database,$sql);

                                $options = array();
                                while ($row = mysqli_fetch_array($result)) {
                                    $options[$row['TEACH_ID']] = $row['TEACH_ID'] . ' - ' . $row['TEACH_SNAME'];
                                }
                            ?>

                            <?php

                                echo '<label for="'.$type.'">Teacher:</label><br>
                                <select name="'.$type.'">';

                                echo '<option value="">Select an option</option>';
                                foreach ($options as $id => $label) {
                                    echo '<option value="' . $id . '">' . $label . '</option>';
                                }
                            ?>
                            </select><br>
                        <?php 
                        } 
                        
                        $type = "teacherID";
                        selection($type);
                        ?>
                        <br>
                        
                        
                        <label for="capacity">Class capacity:</label><br>
                        <input type="text" id="capacity" name="capacity"><br>
                        
                   
                        <input type="submit" name="submit"> <br>                        
                    </form>                        
            </div>
            <div class="formData" style="background-color: #d4c32d; color:black;">

                <!-- form for updating a class record -->
                <h2>Update a class record</h2>
                <!-- search for the class id. class details will fill the rest of the form if the record exists -->
                <form method="post" action="class.php" style="color:black;">
                    <label for="classID">Enter Class ID:</label><br>
                    <input type="text" id="classID" name="classID"><br>
                    <input type="submit" name="classID-submit" id="classID-submit"> <br>   

                 <!-- disabled until a record has been searched for, then the user can change the record and update the database -->
                    <label for="update-name">Class name:</label><br>
                    <input type="text" id="update-name" name="update-name" disabled ><br>

                    <?php
                        $type = "update-teacherID";
                        selection($type);
                    ?>
                    <br>
                    <label for="update-capacity">Capacity:</label><br>
                    <input type="text" id="update-capacity" name="update-capacity" disabled><br>

                    <!-- stores ID after form has been submitted -->
                    <input type="hidden" id="saveID" name="saveID"><br>    
                    
                    <input type="submit" name="update-submit" id="update-submit" disabled> <br>                        
                </form>
                
            </div>

            <div class="formData" style="background-color: pink; color:black;">
            <!-- form for deleting a record -->
                <h2>Delete a class record</h2>
                <!-- user searches for classID and then the record is displayed in a table, allows user to ensure they have selected the correct record -->
                <form method="post" action="class.php" style="color:black;">
                        <label for="deleteClassID">Enter Class ID:</label><br>
                        <input type="text" id="deleteClassID" name="deleteClassID"><br>
                        <input type="submit" name="deleteClassID-submit" id="deleteClassID-submit"> <br> 
                </form>  
            </div>                              
        </div>
            <div class="column-left">
                <div class="formData">
                    <!-- displays the table from database when page loads -->
                    <h2>View Classes</h2>

                    <table border="1" cellspacing="4" cellpadding="4">
                        <thead>
                            <tr>     
                                <th>CLASS_ID</th>
                                <th>CLASS_NAME</th>
                                <th>TEACH_ID</th>
                                <th>CLASS_CAPACITY</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            
                    <?php displayTable(); ?>

                    <?php
                        function displayTable(){
                            // fetches table from database

                            $database = mysqli_connect("localhost", "root","","rishton-academy");
                            $sql = "SELECT * FROM class";
                            $result = mysqli_query($database,$sql);

                            // Check the connection to the database
                            if (!$database) {
                                die("Connection failed: ");
                            }

                            // Clear the previous table data
                            echo '<script>document.getElementById("table-body").innerHTML = "";</script>';

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>
                                        <td>'.$row["CLASS_ID"].'</td>
                                        <td>'.$row["CLASS_NAME"].'</td>
                                        <td>'.$row["TEACH_ID"].'</td>
                                        <td>'.$row["CLASS_CAPACITY"].'</td>
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

    //---------------------------------------------add new class record--------------------------------------------------------------------------------------
    $database = mysqli_connect("localhost", "root","","rishton-academy");
    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }
        
    if (isset($_POST['submit'])) {

            $name = mysqli_real_escape_string($database, $_POST['name']);
            $teacherID = mysqli_real_escape_string($database, $_POST['teacherID']);
            $capacity = mysqli_real_escape_string($database, $_POST['capacity']);

            $stmt = mysqli_prepare($database, "INSERT INTO class (CLASS_NAME,TEACH_ID,CLASS_CAPACITY) VALUES (?,?,?)" );
            mysqli_stmt_bind_param($stmt, "sii", $name,$teacherID,$capacity);
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


    if (isset($_POST['deleteClassID-submit'])) {
        // search for record with class ID
        $deleteClassID = mysqli_real_escape_string($database, $_POST['deleteClassID']);
    
        $stmt = mysqli_prepare($database, "SELECT * FROM class WHERE CLASS_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $deleteClassID);
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
                            <th>CLASS_ID</th>
                            <th>CLASS_NAME</th>
                            <th>TEACH_ID</th>
                            <th>CLASS_CAPACITY</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row["CLASS_ID"]; ?></td>
                            <td><?php echo $row["CLASS_NAME"]; ?></td>
                            <td><?php echo $row["TEACH_ID"]; ?></td>
                            <td><?php echo $row["CLASS_CAPACITY"]; ?></td>
                        </tr>
                    <?php } ?>

                    </table>
                    <!-- ensure user selected the correct record -->
                    <p>Are you sure you want to delete this record?</p>
                    <form method="post">
                        <input type="hidden" name="deleteClassID" value="<?php echo $deleteClassID; ?>">
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
        $deleteClassID = mysqli_real_escape_string($database, $_POST['deleteClassID']);
    
        $sql = "DELETE FROM class WHERE CLASS_ID = '$deleteClassID'";

        $stmt = mysqli_prepare($database, "DELETE FROM class WHERE CLASS_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $deleteClassID);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {            
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record.";
        };
        displayTable();

    };
    
    mysqli_close($database);


// -----------------------update records---------------------------------------

    // establish database connection
    $database = mysqli_connect("localhost", "root","","rishton-academy");

    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // search for classID to update record
    if (isset($_POST['classID-submit'])) {
        $classID = mysqli_real_escape_string($database, $_POST['classID']);
        echo htmlspecialchars($classID);

        $stmt = mysqli_prepare($database, "SELECT * FROM class WHERE CLASS_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $classID);
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
            $updateName = htmlspecialchars($row['CLASS_NAME']);
            $updateTeacherID = htmlspecialchars($row['TEACH_ID']);
            $updateCapacity = htmlspecialchars($row['CLASS_CAPACITY']);

            // add the record data into the input fields in the update form
            // enable the fields on the update record form so user can edit data
            ?>
            <script>
                document.getElementById('update-name').value = '<?= $updateName ?>';
                document.getElementById('update-capacity').value = '<?= $updateCapacity ?>';

                document.getElementById('saveID').value = '<?= $classID ?>';

                document.getElementById('update-name').disabled = false;
                document.getElementById('update-capacity').disabled = false;
                document.getElementById('update-submit').disabled = false;

                document.getElementById('classID').hidden = true;
                document.getElementById('classID-submit').disabled = true;
                document.getElementById('classID-submit').hidden = true;
            </script>
            <?php
        }

    }
    
    
    if (isset($_POST['update-submit'])) {
        $classID =mysqli_real_escape_string($database, $_POST['saveID']);

        $name = mysqli_real_escape_string($database, $_POST['update-name']);
        $teacherID = mysqli_real_escape_string($database, $_POST['update-teacherID']);
        $capacity = mysqli_real_escape_string($database, $_POST['update-capacity']);


        // update the record in the database
        $stmt = mysqli_prepare($database, "UPDATE class SET CLASS_NAME=?, TEACH_ID=?, CLASS_CAPACITY=? WHERE CLASS_ID=?");
        mysqli_stmt_bind_param($stmt, "siii", $name, $teacherID, $capacity, $classID);
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

