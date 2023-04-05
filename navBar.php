<!-- all code is my own unless otherwise stated -->
<!-- for more detailed comments for common functions see student.php -->
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>Rishton Academy Primary School</title>
        <link rel="icon" href="images/rishton-logo-no-bg.png">
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <div class="background">
    
            <div class="navBar">
                <!-- home -->
                <a href="index.php"><img alt="home" src="images/rishton-logo-no-bg.png" width = 100px></a>
                <a href="index.php">Home</a>
    

                <!-- Family -->
                <div class="navLink dropdown">
                    <button class="dropbtn">Family</button>
                    <div class="dropdown-content">
                      <a href="family.php">Manage Family Details</a>
                      <a href="student.php">Manage Pupils</a>
                      <a href="parent.php">Manage Parents</a>
                      <a href="viewFamilies.php">View Families</a>
                    </div>
                </div>

                <!-- teacher -->
                <div class="navLink dropdown">
                    <button class="dropbtn">Staff</button>
                    <div class="dropdown-content">
                        <a href="teacher.php">Manage Teachers</a>
                    </div>
                </div>

                <!-- classes -->
                <div class="navLink dropdown">
                    <button class="dropbtn">Classes</button>
                    <div class="dropdown-content">
                        <a href="class.php">Classes</a>
                        <a href="registers.php">Registers</a>
                    </div>
                </div>

                <!-- Salary -->
                <a href="salary.php">Salary</a>

                <!-- statistics -->
                <a href="statistics.php">Statistics</a>
                
                <!-- contact -->
                <a href="contact.php">Contact</a>

                
            </div>
        </div>
    </body>
</html>