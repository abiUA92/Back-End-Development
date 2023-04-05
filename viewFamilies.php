<!-- all code is my own unless otherwise stated -->
<!-- for more detailed comments for common functions see student.php -->
<html>
    <?php 
        include('navBar.php');
        include('functions.php');
    ?>

    <!-- <div class="row"> -->
        <!-- <div class="column-right"> -->
            <div class="formData" style="background-color: orange; color:black;">
                <h3>Search for family members using email address</h3>
                <form method="post" action="viewFamilies.php" style="color:black;">
                    <label for="searchFamilyID">Enter email address:</label><br>
                    <input type="text" id="searchFamilyID" name="searchFamilyID"><br>
                    <input type="submit" name="searchFamilyID-submit" id="searchFamilyID-submit"> <br> 
                </form> 
            </div>
        <!-- </div>
    </div> -->

</html>

<?php
    $database = mysqli_connect("localhost", "root","","rishton-academy");
    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }
    if (isset($_POST['searchFamilyID-submit'])) {
        // search for record with family email
        $searchFamilyID = mysqli_real_escape_string($database, $_POST['searchFamilyID']);
        $familyID = searchFamilyID($searchFamilyID);

        if (!(empty($familyID))){
    
            $stmt = mysqli_prepare($database, "SELECT * FROM parent WHERE FAMILY_ID = ?");
            mysqli_stmt_bind_param($stmt, "i", $familyID);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
    
            if (mysqli_num_rows($result) > 0) {
            ?>
                <div class="formData">
                    <table border="1" cellspacing="4" cellpadding="4" style="border-color:blue;">
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
                    </table><br>
                </div>
            <?php
                } else {
                    echo "Parent record/s not found <br>";
                }

                $stmt = mysqli_prepare($database, "SELECT * FROM pupil WHERE FAMILY_ID = ?");
                mysqli_stmt_bind_param($stmt, "i", $familyID);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
        
                if (mysqli_num_rows($result) > 0) {
                ?>
                    <div class="formData">
                        <table border="1" cellspacing="4" cellpadding="4" style="border-color:yellow;">
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
                    </div>
                <?php
                    } else {
                        echo "Pupil record/s not found";
                    }
        }
    };

?>
