<!-- all code is my own unless otherwise stated -->
<!-- for more detailed comments for common functions see student.php -->
<html>
    <?php 
        include('navBar.php');
        include('functions.php');
    ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>
<body>

<canvas id="myChart" style="width:100%;max-width:700px; margin:50px auto 0 auto;"></canvas>

<script>
    <?php
        // establish database connection
        $database = mysqli_connect("localhost", "root","","rishton-academy");

        // check if the connection is successful
        if (!$database) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // get all class ids from pupil table
        $sql="SELECT CLASS_ID FROM pupil";
        $result = mysqli_query($database, $sql);

        $year1 = 0; 
        $year2 = 0;
        $year3 = 0;
        $year4 = 0;
        $year5 = 0;
        $year6 = 0;
        $reception = 0;

        while ($row = $result->fetch_assoc()){
            if ($row['CLASS_ID']=="1"){
                $year1 ++;
            }elseif($row['CLASS_ID']=="2"){
                $year2 ++;
            }elseif($row['CLASS_ID']=="3"){
                $year3 ++;
            }elseif($row['CLASS_ID']=="4"){
                $year4 ++;
            }elseif($row['CLASS_ID']=="5"){
                $year5 ++;
            }elseif($row['CLASS_ID']=="6"){
                $year6 ++;
            }elseif($row['CLASS_ID']=="7"){
                $reception ++;
            };

        }


    ?>

    var xValues = ["Year 1", "Year 2", "Year 3", "Year 4", "Year 5", "Year 6", "Reception"];
    // get variables from php
    var yValues = [<?php echo $year1; ?>,<?php echo $year2; ?>,<?php echo $year3; ?>,<?php echo $year4; ?>,<?php echo $year5; ?>,<?php echo $year6; ?>,<?php echo $reception; ?>];
    // colour for each segment in pie chart
    var segmentColours = [
    "#ff2501", //year 1 
    "#fe941e", //year 2
    "#fbfb39", //year 3
    "#02ae23", //year 4
    "#5ce1e6", //year 5
    "#081ca1", //year 6
    "#c7237a" //reception
    ];
// make pie chart using functions from library 
    new Chart("myChart", {
    type: "pie",
    data: {
        labels: xValues,
        datasets: [{
            backgroundColor: segmentColours,
            data: yValues,
            color: "#FFF"
        }]
    },
    options: {
        title: {
            display: true,
            text: "Number of students in each class",
            fontColor: "#FFF"
        },
        legend: {
            labels: {
                fontColor: "#FFF"
            }
        },
        plugins: {
            datalabels: {
                color: "#FFF"
            }
        }
    }
});

</script>

</body>
</html>