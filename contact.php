<!-- all code is my own unless otherwise stated -->
<!-- for more detailed comments for common functions see student.php -->
<html>
    <link rel="stylesheet" href="style.css">
    <?php 
        include('navBar.php');
        include('functions.php');
    ?>

    <div class="formData contact">
    <!-- form for entering a new salary record -->
        <h2>Contact Rishton Academy</h2>
        <!-- use schools logo colours as background -->
        <form method="post" action="contact.php" style="background-image: 
        linear-gradient(to bottom right, rgb(92, 225, 230), #e9de2a, #f09bc5); color:black; border-radius:30px; ">
            <label for="fname">First Name:</label><br>
            <input type="text" id="fname" name="fname"><br>            
                        
            <label for="lname">Last name:</label><br>
            <input type="text" id="lname" name="lname"><br>  
            
            <label for="email">Email Address:</label><br>
            <input type="email" id="email" name="email"><br>

            <label for="message">Message:</label><br>
            <textarea type="text" id="message" name="message"></textarea><br> 
            
            <input type="submit" name="submit"> <br>                        
        </form>                        
    </div>
</html>

<?php
    if (isset($_POST['submit'])) {

        // example email to be replaced with a school email address to receive messages
        $to = "example@email.com";
        $subject = "Rishton Academy Contact Form";
        // get data from form 
        $message = $_POST['message'];
        $fname = $_POST['fname'];
        $lname = $_POST['lname'];
        $email = $_POST['email'];

        // creates a message
        $txt = "message from: ".$fname." ".$lname." ". $email." Message: ".$message;

        // needs to be connected to a mail server to send the email
        mail($to,$subject,$txt);
    };
?>