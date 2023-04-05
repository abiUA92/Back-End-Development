<!-- all code is my own unless otherwise stated -->
<!-- for more detailed comments for common functions see student.php -->
<html>
    <?php 
        include('navBar.php');
        include('functions.php');
    ?>

    <h1>Salary</h1>
    <div class="row">
        <div class="column-right">
            <div class="formData" style="background-color: blue;">
                <!-- form for entering a new salary record -->
                    <h2>Enter new salary record</h2>
                    
                    <form method="post" action="salary.php">
                        <label for="spinePoint">Spine Point:</label><br>
                        <input type="text" id="spinePoint" name="spinePoint"><br>
                        
                        <label for="pay">Pay:</label><br>
                        <input type="text" id="pay" name="pay"><br>                 
                        
                        <input type="submit" name="submit"> <br>                        
                    </form>                        
            </div>
            <div class="formData" style="background-color: #d4c32d; color:black;">

                <!-- form for updating a salary record -->
                <h2>Update a salary record</h2>
                <!-- search for the salary id. salary details will fill the rest of the form if the record exists -->
                <form method="post" action="salary.php" style="color:black;">
                    <label for="salaryID">Enter Salary ID:</label><br>
                    <input type="text" id="salaryID" name="salaryID"><br>
                    <input type="submit" name="salaryID-submit" id="salaryID-submit"> <br>   

                <!-- disabled until a record has been searched for, then the user can change the record and update the database -->
                    <label for="update-spinePoint">Spine Point:</label><br>
                    <input type="text" id="update-spinePoint" name="update-spinePoint" disabled ><br>

                    <label for="update-pay">Pay:</label><br>
                    <input type="text" id="update-pay" name="update-pay" disabled><br>

                    <!-- stores ID after form has been submitted -->
                    <input type="hidden" id="saveID" name="saveID"><br>

                    
                    <input type="submit" name="update-submit" id="update-submit" disabled> <br>                        
                </form>
                
            </div>

            <div class="formData" style="background-color: pink; color:black;">
            <!-- form for deleting a record -->
                <h2>Delete a salary record</h2>
                <!-- user searches for salaryID and then the record is displayed in a table, allows user to ensure they have selected the correct record -->
                <form method="post" action="salary.php" style="color:black;">
                        <label for="deleteSalaryID">Enter Salary ID:</label><br>
                        <input type="text" id="deleteSalaryID" name="deleteSalaryID"><br>
                        <input type="submit" name="deleteSalaryID-submit" id="deleteSalaryID-submit"> <br> 
                </form>  
            </div>                              
        </div>
            <div class="column-left">
                <div class="formData">
                    <!-- displays the table from database when page loads -->
                    <h2>View salary</h2>

                    <table border="1" cellspacing="4" cellpadding="4">
                        <thead>
                            <tr>     
                                <th>SALARY_ID</th>
                                <th>SPINE_POINT</th>
                                <th>PAY</th>
                            </tr>
                        </thead>
                        <tbody id="table-body">
                            
                    <?php displayTable(); ?>

                    <?php
                        function displayTable(){
                            // fetches table from database

                            $database = mysqli_connect("localhost", "root","","rishton-academy");
                            $sql = "SELECT * FROM salary";
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
                                        <td>'.$row["SALARY_ID"].'</td>
                                        <td>'.$row["SPINE_POINT"].'</td>
                                        <td>'.$row["PAY"].'</td>
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

    //---------------------------------------------add new salary record--------------------------------------------------------------------------------------
    $database = mysqli_connect("localhost", "root","","rishton-academy");
    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }
        
    if (isset($_POST['submit'])) {

            $spinePoint = mysqli_real_escape_string($database, $_POST['spinePoint']);
            $pay = mysqli_real_escape_string($database, $_POST['pay']);

           

            salaryValidation($spinePoint,$pay);

            $stmt = mysqli_prepare($database, "INSERT INTO salary (SPINE_POINT,PAY) VALUES (?,?)" );
            mysqli_stmt_bind_param($stmt, "si", $spinePoint,$pay);
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


    if (isset($_POST['deleteSalaryID-submit'])) {
        // search for record with salary ID
        $deleteSalaryID = mysqli_real_escape_string($database, $_POST['deleteSalaryID']);
    
        $stmt = mysqli_prepare($database, "SELECT * FROM salary WHERE SALARY_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $deleteSalaryID);
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
                                <th>SALARY_ID</th>
                                <th>SPINE_POINT</th>
                                <th>PAY</th>
                            </tr>

                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $row["SALARY_ID"]; ?></td>
                                <td><?php echo $row["SPINE_POINT"]; ?></td>
                                <td><?php echo $row["PAY"]; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                    <!-- ensure user selected the correct record -->
                    <p>Are you sure you want to delete this record?</p>
                    <form method="post">
                        <input type="hidden" name="deleteSalaryID" value="<?php echo $deleteSalaryID; ?>">
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
        $deleteSalaryID = mysqli_real_escape_string($database, $_POST['deleteSalaryID']);
    
        $sql = "DELETE FROM salary WHERE SALARY_ID = '$deleteSalaryID'";

        $stmt = mysqli_prepare($database, "DELETE FROM salary WHERE SALARY_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $deleteSalaryID);
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

    // search for salaryID to update record
    if (isset($_POST['salaryID-submit'])) {
        $salaryID = mysqli_real_escape_string($database, $_POST['salaryID']);
        echo htmlspecialchars($salaryID);

        $stmt = mysqli_prepare($database, "SELECT * FROM salary WHERE SALARY_ID = ?");
        mysqli_stmt_bind_param($stmt, "i", $salaryID);
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
            $updateSpinePoint = htmlspecialchars($row['SPINE_POINT']);
            $updatePay = htmlspecialchars($row['PAY']);

            // add the record data into the input fields in the update form
            // enable the fields on the update record form so user can edit data
            ?>
            <script>
                document.getElementById('update-spinePoint').value = '<?= $updateSpinePoint ?>';
                document.getElementById('update-pay').value = '<?= $updatePay ?>';

                document.getElementById('saveID').value = '<?= $salaryID ?>';

                document.getElementById('update-spinePoint').disabled = false;
                document.getElementById('update-pay').disabled = false;
                document.getElementById('update-submit').disabled = false;

                document.getElementById('salaryID').hidden = true;
                document.getElementById('salaryID-submit').disabled = true;
                document.getElementById('salaryID-submit').hidden = true;
            </script>
            <?php
        }


    }
    
    
    if (isset($_POST['update-submit'])) {
        $salaryID =mysqli_real_escape_string($database, $_POST['saveID']);
        $spinePoint = mysqli_real_escape_string($database, $_POST['update-spinePoint']);
        $pay = mysqli_real_escape_string($database, $_POST['update-pay']);

        // perform validation and sanitization for user input
        salaryValidation($spinePoint,$pay);
        

        // update the record in the database
        $stmt = mysqli_prepare($database, "UPDATE salary SET SPINE_POINT=?, PAY=? WHERE SALARY_ID=?");
        mysqli_stmt_bind_param($stmt, "sii", $spinePoint, $pay, $salaryID);
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
