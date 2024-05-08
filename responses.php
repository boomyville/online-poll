<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<?php
error_reporting(E_ALL);
ini_set("display_errors", 1);
// Do the PHP stuff here
// Mainly an associative array with each individual response % values and the question being the key
// Keys: id, time, poll_id [2], ip, value [4]

include "config.php"; //Includes connection to the database

//Grab poll data
$query2 = mysqli_query($con, "SELECT id from poll where category='random'");
$query3 = mysqli_query($con, "SELECT question from poll where category='random'");
$row2 = mysqli_fetch_all($query2); // Grab the poll_id
$row3 = mysqli_fetch_all($query3); // Grab the questions (string)

$questions = [];
$responses = [];
$answers = [];

for($i = 0; $i < count($row2); $i++) {
    //echo "poll id: " . $row2[$i][0] . " | ". $row3[$i][0] . "<br>";
   
    array_push($questions, $row3[$i][0]);

    //Grab a count value for each poll_id option (0 - 4)
    $id = $row2[$i][0];
    $query = mysqli_query($con, "SELECT * FROM poll WHERE id = '$id'");
    $row = mysqli_fetch_assoc($query); // Grab how many of option 0 is present

    $query4 = mysqli_query($con, "SELECT COUNT(*) AS count FROM responses WHERE value=0 AND poll_id='$id'");
    $row4 = mysqli_fetch_assoc($query4); // Grab how many of option 0 is present
    //echo $row4['count'] . " responses for the option " . $row['answer'] . "<br>";

    $responses[$row['answer']] = $row4['count'];

    $query5 = mysqli_query($con, "SELECT COUNT(*) AS count FROM responses WHERE value=1 AND poll_id='$id'");
    $row5 = mysqli_fetch_assoc($query5); // Grab how many of option 0 is present
    //echo $row5['count'] . " responses for the option " . $row['option1'] . "<br>";

    $responses[$row['option1']] = $row5['count'];

    $query6 = mysqli_query($con, "SELECT COUNT(*) AS count FROM responses WHERE value=2 AND poll_id='$id'");
    $row6 = mysqli_fetch_assoc($query6); // Grab how many of option 0 is present
    //echo $row6['count'] . " responses for the option " . $row['option2'] . "<br>";

    $responses[$row['option2']] = $row6['count'];

    $query7 = mysqli_query($con, "SELECT COUNT(*) AS count FROM responses WHERE value=3 AND poll_id='$id'");
    $row7 = mysqli_fetch_assoc($query7); // Grab how many of option 0 is present
    //echo $row7['count'] . " responses for the option " . $row['option3'] . "<br>";

    $responses[$row['option3']] = $row7['count'];

    $query8 = mysqli_query($con, "SELECT COUNT(*) AS count FROM responses WHERE value=4 AND poll_id='$id'");
    $row8 = mysqli_fetch_assoc($query8); // Grab how many of option 0 is present
    //echo $row8['count'] . " responses for the option " . $row['option4'] . "<br>";

    $responses[$row['option4']] = $row8['count'];

    $answers[$row3[$i][0]] = $responses;
    $responses = [];
    
// Print charts
echo '<canvas id=' . str_replace(' ', '', $row3[$i][0]) . ' style="width:100%;max-width:600px;max-height:200px;"></canvas>';
}

//print_r($answers);


?>

<script>
    var data = <?php echo json_encode($answers); ?>;
    let nameArr = [];
let x = [];
let y = [];
for(var key in data) {
    nameArr.push(key);
    x[key] = [];
    y[key] = [];
    for(var key2 in data[key]) {
        y[key].push(parseInt(data[key][key2]));
        x[key].push(key2);
    }
}



for(var key in nameArr) {
    var ctx = document.getElementById(nameArr[key].replaceAll(" ", "")).getContext("2d");
    new Chart(ctx, {
  type: "bar",
  data: {
    labels: x[nameArr[key]],
    datasets: [{
      data: y[nameArr[key]]
    }]
  },
  options: {
      plugins: {
    legend: {display: false},
    title: {
      display: true,
      text: nameArr[key]
    }
      }
  }
});
}


       
</script>
