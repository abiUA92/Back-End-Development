<!-- all code is my own unless otherwise stated -->
<!-- for more detailed comments for common functions see student.php -->
<?php

function validation($fname,$lname,$family,$class,$DOB,$phone){
    // perform validation and sanitization for user input
    if (empty($fname) || empty($lname) || empty($family) || empty($class) || empty($DOB)|| empty($phone)) {
        echo "Please fill in all required fields.";
        exit;
    }

    if (!ctype_alpha($fname) || !ctype_alpha($lname)) {
        echo "Name fields can only contain alphabetical characters.";
        exit;
    }

    if (!ctype_digit($family) || !ctype_digit($class)) {
        echo "Family ID and Class ID fields can only contain numerical characters.";
        exit;
    }
    if (!ctype_digit($phone)) {
        echo "Phone number can only contain numerical characters.";
        exit;
    }
    if (11<(strlen($phone)) or 11>(strlen($phone)) ) {
        echo "Phone number must contain 11 digits.";
        exit;
    }
    };

function searchFamilyID($searchFamilyID){

    $database = mysqli_connect("localhost", "root","","rishton-academy");
    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $stmt = mysqli_prepare($database, "SELECT * FROM family WHERE FAMILY_EMAIL = ?");
    mysqli_stmt_bind_param($stmt, "s", $searchFamilyID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // display single record to user if the record is found
    if (mysqli_num_rows($result) > 0) {
    ?>
        <div class="formData">
            <form method="post">
                <table border="1" cellspacing="4" cellpadding="4" style="border-color:orange;">
                    <tr>
                        <td>FAMILY_ID</td>
                        <td>FAMILY_ADDRESS</td>
                        <td>FAMILY_EMAIL</td>
                    </tr>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row["FAMILY_ID"]; ?></td>
                            <td><?php echo $row["FAMILY_ADDRESS"]; ?></td>
                            <td><?php echo $row["FAMILY_EMAIL"]; ?></td>
                        </tr>
                        <?php 
                        $familyID = $row["FAMILY_ID"];
                    } 
                    mysqli_stmt_close($stmt);?>
                    
                </table>
                    <!-- clear table from screen when button pressed -->
                <form method="post">
                    <button type="button" onclick="history.back()">Clear</button>
                </form>
            </form>
        </div>
    <?php
            return $familyID;
        } else {
            echo "Record not found";
        }
    ?>
    <?php 
        mysqli_close($database);
        };


 //validation and sanitization for user inputs
 function salaryValidation($spinePoint,$pay){
    if (empty($spinePoint) || empty($pay)) {
        echo "Please fill in all required fields.";
    exit;
    }
    if (!ctype_digit($pay)) {
        echo "Pay field can only contain numerical characters.";
        exit;
    }
};
 function familyValidation($address,$email){
    if (empty($address) || empty($email)) {
        echo "Please fill in all required fields.";
    exit;
    }
};
 function teacherValidation($fname, $lname,$address,$phone,$salary){
    if (empty($fname) || empty($lname)|| empty($address)|| empty($phone)|| empty($salary)) {
        echo "Please fill in all required fields.";
    exit;
    }
    if (11<(strlen($phone)) or 11>(strlen($phone)) ) {
        echo "Phone number must contain 11 digits.";
        exit;
    }
};


function checkFamilyID($family){
    $database = mysqli_connect("localhost", "root","","rishton-academy");
    // check if the connection is successful
    if (!$database) {
        die("Connection failed: " . mysqli_connect_error());
    }

    $sql = "SELECT FAMILY_ID FROM family";
    $result = mysqli_query($database,$sql);
    $found = false;

    while ($row = mysqli_fetch_array($result)) {
        if ($row['FAMILY_ID'] == $family) {
            $found = true;
            break; // exit the loop since the value is found
        }
    }

    return $found;
}

?>