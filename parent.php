<!-- all code is my own unless otherwise stated -->
<!-- for more detailed comments for common functions see student.php -->
<html>
    <?php 
        include('navBar.php');
        include('functions.php')
    ?>

    <h1>Parent</h1>
    <div class="row">
        <div class="column-right">
            <div class="formData" style="background-color: blue;">
                <!-- form for entering a new parent record -->
                    <h2>Enter new parent record</h2>
                    
                    <form method="post" action="parent.php">
                        <label for="fname">First name:</label><br>
                        <input type="text" id="fname" name="fname"><br>
                        
                        <label for="lname">Last name:</label><br>
                        <input type="text" id="lname" name="lname"><br>
                        
                        <label for="family">Family ID:</label><br>
                        <input type="text" id="family" name="family"><br>
                        
                        <label for="phone">Phone Number:</label><br>
                        <input type="text" id="phone" name="phone"><br>                    
                        
                        <input type="submit" name="submit"> <br>                        
                    </form>                        
            </div>
            <div class="formData" style="background-color: #d4c32d; color:black;">

                <!-- form for updating a parent record -->
                <h2>Update a parent's record</h2>
                <!-- search for the parent id. parents details will fill the rest of the form if the record exists -->
                <form method="post" action="parent.php" style="color:black;">
                    <label for="parentID">Enter Parent ID:</label><br>
                    <input type="text" id="parentID" name="parentID"><br>
                    <input type="submit" name="parentID-submit" id="parentID-submit"> <br>   

                <!-- disabled until a record has been searched for, then the user can change the record and update the database -->
                    <label for="update-fname">First name:</label><br>
                    <input type="text" id="update-fname" name="update-fname" disabled ><br>

                    <label for="update-lname">Last name:</label><br>
                    <input type="text" id="update-lname" name="update-lname" disabled><br>
                    
                    <label for="update-family">Family ID:</label><br>
                    <input type="text" id="update-family" name="update-family" disabled><br>
                    
                    <label for="update-phone">Phone Number:</label><br>
                    <input type="text" id="update-phone" name="update-phone" disabled><br>

                    <!-- stores ID after form has been submitted -->
                    <input type="hidden" id="saveID" name="saveID"><br>
                    
                    <input type="submit" name="update-submit" id="update-submit" disabled> <br>                        
                </form>
                
            </div>

            <div class="formData" style="background-color: pink; color:black;">
            <!-- form for deleting a record -->
                <h2>Delete a parent record</h2>
                <!-- user searches for parentID and then the record is displayed in a table, allows user to ensure they have selected the correct record -->
                <form method="post" action="parent.php" style="color:black;">
                        <label for="deleteParentID">Enter Parent ID:</label><br>
                        <input type="text" id="deleteParentID" name="deleteParentID"><br>
                        <input type="submit" name="deleteParentID-submit" id="deleteParentID-submit"> <br> 
                </form>  
            </div>                              
            <!-- allow user to find family id here instead of navigating to family page -->
            <div class="formData" style="background-color: orange; color:black;">
                <h3>Search for family ID using email address</h3>
                <form method="post" action="parent.php" style="color:black;">
                    <label for="searchFamilyID">Enter email address:</label><br>
                    <input type="text" id="searchFamilyID" name="searchFamilyID"><br>
                    <input type="submit" name="searchFamilyID-submit" id="searchFamilyID-submit"> <br> 
                </form> 
            </div>
        </div>
            <div class="column-left">
                <div class="formData">
                    <!-- displays the table from database when page loads -->
                    <h2>View parents</h2>
                    <h3 style="color:red;">The Family ID is required to add parents and parents to the database.<br> Use the orange search box below to search for ID using <br> an email address.<h3>

                    <table border="1" cellspacing="4" cellpadding="4">
                        <thead>
                            <tr>     
                                <th>PARENT_ID</th>
                                <th>FAMILY_ID</th>
                                <th>PARENT_FNAME</th>
                                <th>PARENT_SNAME</th>
                                <th>PARENT_PHONE</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            
                    <?php displayTable(); ?>

                    <?php
                        function displayTable(){
                            // fetches table from database

                            $database = mysqli_connect("localhost", "root","","rishton-academy");
                            $sql = "SELECT * FROM parent";
                            $result = mysqli_query($database,$sql);

                            // Check the connection to the database
                            if (!$database) {
                                die("Connection failed: ");
                            }

                            // Clear the previous table data
                            echo '<script>document.getElementById("table-body").innerHTML = "";</script>';

                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr>
                                        <td>'.$row["PARENT_ID"].'</td>
                                        <td>'.$row["FAMILY_ID"].'</td>
                                        <td>'.$row["PARENT_FNAME"].'</td>
                                        <td>'.$row["PARENT_SNAME"].'</td>
                                        <td>'.$row["PARENT_PHONE"].'</td>
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

    //---------------------------------------------add new parent record--------------------------------------------------------------------------------------
    $database = mysqli_connect("localhost", "root","","rishton-academy");
    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }
        
    if (isset($_POST['submit'])) {

            $fname = mysqli_real_escape_string($database, $_POST['fname']);
            $lname = mysqli_real_escape_string($database, $_POST['lname']);
            $family = mysqli_real_escape_string($database, $_POST['family']);
            $phone = mysqli_real_escape_string($database, $_POST['phone']);

            
            $found = checkFamilyID($family);
            
            if (!$found) {
                echo "Invalid Family ID";
                exit;
            }

            validation($fname,$lname,$family,"000","000",$phone);

            $stmt = mysqli_prepare($database, "INSERT INTO parent (FAMILY_ID,PARENT_FNAME,PARENT_SNAME,PARENT_PHONE) VALUES (?,?,?,?)" );
            mysqli_stmt_bind_param($stmt, "isss", $family,$fname,$lname,$phone);
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


    if (isset($_POST['deleteParentID-submit'])) {
        // search for record with parent ID
        $deleteParentID = mysqli_real_escape_string($database, $_POST['deleteParentID']);
    
        $stmt = mysqli_prepare($database, "SELECT * FROM parent WHERE PARENT_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $deleteParentID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // display single record to user if the record is found
        if (mysqli_num_rows($result) > 0) {
        ?>
            <div class="formData">
                <form method="post">
                    <table border="1" cellspacing="4" cellpadding="4" style="border-color:pink;">
                        <tr>
                            <td>PARENT_ID</td>
                            <td>FAMILY_ID</td>
                            <td>PARENT_FNAME</td>
                            <td>PARENT_SNAME</td>
                            <td>PARENT_PHONE</td>
                        </tr>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row["PARENT_ID"]; ?></td>
                                <td><?php echo $row["FAMILY_ID"]; ?></td>
                                <td><?php echo $row["PARENT_FNAME"]; ?></td>
                                <td><?php echo $row["PARENT_SNAME"]; ?></td>
                                <td><?php echo $row["PARENT_PHONE"]; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                    <!-- ensure user selected the correct record -->
                    <p>Are you sure you want to delete this record?</p>
                    <form method="post">
                        <input type="hidden" name="deleteParentID" value="<?php echo $deleteParentID; ?>">
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
        $deleteParentID = mysqli_real_escape_string($database, $_POST['deleteParentID']);
    
        $sql = "DELETE FROM parent WHERE PARENT_ID = '$deleteParentID'";

        $stmt = mysqli_prepare($database, "DELETE FROM parent WHERE PARENT_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $deleteParentID);
        $result = mysqli_stmt_execute($stmt);

        if ($result) {            
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record.";
        };
        displayTable();

    };
    
    mysqli_close($database);




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





    // -----------------------update records---------------------------------------

    // establish database connection
    $database = mysqli_connect("localhost", "root","","rishton-academy");

    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }

    // search for parentID to update record
    if (isset($_POST['parentID-submit'])) {
        $parentID = mysqli_real_escape_string($database, $_POST['parentID']);
        echo htmlspecialchars($parentID);

        $stmt = mysqli_prepare($database, "SELECT * FROM parent WHERE PARENT_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $parentID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // If the primary key is found, fill in the text boxes with the data
        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $updateFamily = htmlspecialchars($row['FAMILY_ID']);
            $updateFname = htmlspecialchars($row['PARENT_FNAME']);
            $updateSname = htmlspecialchars($row['PARENT_SNAME']);
            $updatePhone = htmlspecialchars($row['PARENT_PHONE']);



            // add the record data into the input fields in the update form
            // enable the fields on the update record form so user can edit data
            ?>
            <script>
                document.getElementById('update-fname').value = '<?= $updateFname ?>';
                document.getElementById('update-lname').value = '<?= $updateSname ?>';
                document.getElementById('update-family').value = '<?= $updateFamily ?>';
                document.getElementById('update-phone').value = '<?= $updatePhone ?>';
                document.getElementById('saveID').value = '<?= $parentID ?>';

                document.getElementById('update-fname').disabled = false;
                document.getElementById('update-lname').disabled = false;
                document.getElementById('update-family').disabled = false;
                document.getElementById('update-phone').disabled = false;
                document.getElementById('update-submit').disabled = false;

                document.getElementById('parentID').hidden = true;
                document.getElementById('parentID-submit').disabled = true;
                document.getElementById('parentID-submit').hidden = true;
            </script>
            <?php
        }


    }
    
    
    if (isset($_POST['update-submit'])) {
        $parentID =mysqli_real_escape_string($database, $_POST['saveID']);
        $fname = mysqli_real_escape_string($database, $_POST['update-fname']);
        $lname = mysqli_real_escape_string($database, $_POST['update-lname']);
        $family = mysqli_real_escape_string($database, $_POST['update-family']);
        $phone = mysqli_real_escape_string($database, $_POST['update-phone']);

                    
        $found = checkFamilyID($family);
            
        if (!$found) {
            echo "Invalid Family ID";
            exit;
        }

        // perform validation and sanitization for user input
        validation($fname,$lname,$family,"000","000",$phone);
        

        // update the record in the database
        $stmt = mysqli_prepare($database, "UPDATE parent SET FAMILY_ID=?, PARENT_FNAME=?, PARENT_SNAME=?, PARENT_PHONE=? WHERE PARENT_ID=?");
        mysqli_stmt_bind_param($stmt, "isssi", $family, $fname, $lname, $phone, $parentID);
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