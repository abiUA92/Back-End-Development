<!-- all code is my own unless otherwise stated -->
<!-- for more detailed comments for common functions see student.php -->
<html>
        <?php 
            include('navBar.php'); 
            include('functions.php')
        ?>

    <div class = "row">
        <div class="column-right">

            <div class="formData" style="background-color: blue;">

            <!-- form for entering a new family record -->
                <h2>Enter new family record</h2>
                
                <form method="post" action="family.php">
                    <label for="email">Email Address:</label><br>
                    <input type="email" id="email" name="email"><br>
                    
                    <label for="address">Home Address:</label><br>
                    <textarea type="text" id="address" name="address"></textarea><br> 
                    
                    <input type="submit" name="submit"> <br>                        
                </form>
                
            </div>
            <div class="formData" style="background-color: #d4c32d; color:black;">
                <!-- form for updating a family record -->
                <h2>Update a family's record</h2>
                <!-- search for the family id. families details will fill the rest of the form if the record exists -->
                <form method="post" action="family.php" style="color:black;">
                    <label for="familyID">Enter Family ID:</label><br>
                    <input type="text" id="familyID" name="familyID"><br>
                    <input type="submit" name="familyID-submit" id="familyID-submit"> <br>   

                <!-- disabled until a record has been searched for, then the user can change the record and update the database -->
                    <label for="email">Email Address:</label><br>
                    <input type="email" id="update-email" name="update-email" disabled><br>
                    
                    <label for="address">Home Address:</label><br>
                    <textarea type="text" id="update-address" name="update-address" disabled></textarea><br> 

                    <!-- stores ID after form has been submitted -->
                    <input type="hidden" id="saveID" name="saveID"><br>
                    
                    <input type="submit" name="update-submit" id="update-submit" disabled> <br>                        
                </form>                
            </div>
            <div class="formData" style="background-color: pink; color:black;">
            <!-- form for deleting a record -->
                <h2>Delete a family record</h2>
                <!-- user searches for familyID and then the record is displayed in a table, allows user to ensure they have selected the correct record -->
                <form method="post" action="family.php" style="color:black;">
                        <label for="deleteFamilyID">Enter Family ID:</label><br>
                        <input type="text" id="deleteFamilyID" name="deleteFamilyID"><br>
                        <input type="submit" name="deleteFamilyID-submit" id="deleteFamilyID-submit"> <br> 
                </form>  
            </div>                              

        </div>
        <div class="column-left">
            <div class="formData">
                <!-- displays the table from database when page loads -->
                <h2>View families</h2>

                <table border="1" cellspacing="4" cellpadding="4">
                    <thead>
                        <tr>     
                            <th>FAMILY_ID</th>
                            <th>FAMILY_ADDRESS</th>
                            <th>FAMILY_EMAIL</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">
                        
                <?php displayTable(); ?>

                <?php
                function displayTable(){
                    // fetches table from database

                    $database = mysqli_connect("localhost", "root","","rishton-academy");
                    $sql = "SELECT * FROM family";
                    $result = mysqli_query($database,$sql);

                    // Check the connection to the database
                    if (!$database) {
                        die("Connection failed: ");
                    }

                    // Clear the previous table data
                    echo '<script>document.getElementById("table-body").innerHTML = "";</script>';

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo '<tr>
                                <td>'.$row["FAMILY_ID"].'</td>
                                <td>'.$row["FAMILY_ADDRESS"].'</td>
                                <td>'.$row["FAMILY_EMAIL"].'</td>
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

    //---------------------------------------------add new family record--------------------------------------------------------------------------------------
    $database = mysqli_connect("localhost", "root","","rishton-academy");
    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }
        
    if (isset($_POST['submit'])) {

            $address = mysqli_real_escape_string($database, $_POST['address']);
            $email = mysqli_real_escape_string($database, $_POST['email']);

            familyValidation($address,$email);


            $stmt = mysqli_prepare($database, "INSERT INTO family (FAMILY_ADDRESS, FAMILY_EMAIL) VALUES (?,?)" );
            mysqli_stmt_bind_param($stmt, "ss", $address,$email);
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


    if (isset($_POST['deleteFamilyID-submit'])) {
        // search for record with family ID
        $deleteFamilyID = mysqli_real_escape_string($database, $_POST['deleteFamilyID']);
    
        $stmt = mysqli_prepare($database, "SELECT * FROM family WHERE FAMILY_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $deleteFamilyID);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // display single record to user if the record is found
        if (mysqli_num_rows($result) > 0) {
        ?>
            <div class="formData">
                <form method="post">
                    <table border="1" cellspacing="4" cellpadding="4" style="border-color:pink;">
                        <tr>
                            <td>FAMILY_ID</td>
                            <td>FAMILY_ADDRESS</td>
                            <td>FAMILY_EMAIL/td>
                        </tr>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row["FAMILY_ID"]; ?></td>
                                <td><?php echo $row["FAMILY_ADDRESS"]; ?></td>
                                <td><?php echo $row["FAMILY_EMAIL"]; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                    <!-- ensure user selected the correct record -->
                    <p>Are you sure you want to delete this record?</p>
                    <form method="post">
                        <input type="hidden" name="deleteFamilyID" value="<?php echo $deleteFamilyID; ?>">
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
        $deleteFamilyID = mysqli_real_escape_string($database, $_POST['deleteFamilyID']);
    
        $sql = "DELETE FROM family WHERE FAMILY_ID = '$deleteFamilyID'";

        $stmt = mysqli_prepare($database, "DELETE FROM family WHERE FAMILY_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $deleteFamilyID);
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

    // search for familyID to update record
    if (isset($_POST['familyID-submit'])) {
        $familyID = mysqli_real_escape_string($database, $_POST['familyID']);

        $stmt = mysqli_prepare($database, "SELECT * FROM family WHERE FAMILY_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $familyID);
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
            $updateAddress = htmlspecialchars($row['FAMILY_ADDRESS']);
            $updateEmail = htmlspecialchars($row['FAMILY_EMAIL']);

            // add the record data into the input fields in the update form
            // enable the fields on the update record form so user can edit data
            ?>
            <script>
                document.getElementById('update-address').value = '<?= $updateAddress ?>';
                document.getElementById('update-email').value = '<?= $updateEmail ?>';

                document.getElementById('saveID').value = '<?= $familyID ?>';

                document.getElementById('update-address').disabled = false;
                document.getElementById('update-email').disabled = false;
                document.getElementById('update-submit').disabled = false;

                document.getElementById('familyID').hidden = true;
                document.getElementById('familyID-submit').disabled = true;
                document.getElementById('familyID-submit').hidden = true;
            </script>
            <?php
        }


    }
    
    
    if (isset($_POST['update-submit'])) {
        $familyID =mysqli_real_escape_string($database, $_POST['saveID']);
        $address = mysqli_real_escape_string($database, $_POST['update-address']);
        $email = mysqli_real_escape_string($database, $_POST['update-email']);

        // perform validation and sanitization for user input
        familyValidation($address,$email);
        

        // update the record in the database
        $stmt = mysqli_prepare($database, "UPDATE family SET FAMILY_ADDRESS=?, FAMILY_EMAIL=? WHERE FAMILY_ID=?");
        mysqli_stmt_bind_param($stmt, "ssi", $address, $email, $familyID);
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

