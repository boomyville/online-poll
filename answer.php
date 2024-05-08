<script
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</script>

<?php
// This is will allow users to answer a poll question
include "config.php"; //Includes connection to the database

// This HTML stores our html which we echo at the end
$HTML = "";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// By default, all random questions will be shown
// A drop list will be present for users to change the questions
if (isset($_POST["submit"]))
{
    // User has selected the type of quiz to complete
    if ($_POST["submit"] == "select")
    {

        $category = strtolower($_POST['category']);
        $tag = strtolower($_POST['tag']);
        if ($tag == "")
        {
            $query = mysqli_query($con, "SELECT * from poll WHERE category = '$category'");
        }
        else
        {
            $query = mysqli_query($con, "SELECT * from poll WHERE category = '$category' AND tag = '$tag'");
        }

        // Grab all random questions and display to user
        // We going to use yucky echo statements because that's why I did back in 2013 and that's what I will do today in 2024
        

        if (mysqli_num_rows($query) == 0)
        {
            $HTML .= "<div class='box'>Something went wrong...</div>";
        }
        else
        {
            // Grab all applicable poll questions
            $all = mysqli_fetch_all($query);
            $poll_id_list = [];

            // Sanitise
            foreach ($all as $poll)
            {
                array_push($poll_id_list, $poll[0]);
            }

            // Shuffle
            shuffle($poll_id_list);
            

            // Iterate through poll ids; if user has attempted them before move to the bottom
            foreach ($poll_id_list as $key => $id)
            {

                $ip = getUserIpAddr();
                // See if a query matches or not
                $query12 = mysqli_query($con, "SELECT * from responses WHERE poll_id = '$id' AND ip = '$ip'");
                if (mysqli_num_rows($query12) > 0)
                {
                    unset($poll_id_list[$key]);
                    array_push($poll_id_list, $id);
                }
            }


            // Truncate
            $poll_id_list = array_slice($poll_id_list, 0, intval($_POST['number']));
            
            //We got some questions, lets iterate through each one
            $HTML .= '<!DOCTYPE html> <html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Keval - Kevin Evaluated</title> <link rel="stylesheet" href="style.css">	 </head> <body>';
            $HTML .= '<div class="box"><form action="" method="post">';

            foreach ($poll_id_list as $id)
            {
                    $query2 = mysqli_query($con, "SELECT * from poll WHERE id = '$id' LIMIT 1");
                    $row = mysqli_fetch_assoc($query2); // Grab the data
                    $HTML .= "<div class='box'><div class='row'>" . $row["question"] . "</div>";

                    $options = ['<div class="row"><label><input type="radio" name="' . $row["id"] . '" value="answer">' . $row["answer"] . "</label></div>", '<div class="row"><label><input type="radio" name="' . $row["id"] . '" value="option1">' . $row["option1"] . "</label></div>", '<div class="row"><label><input type="radio" name="' . $row["id"] . '" value="option2">' . $row["option2"] . "</label></div>", '<div class="row"><label><input type="radio" name="' . $row["id"] . '" value="option3">' . $row["option3"] . "</label></div>", '<div class="row"><label><input type="radio" name="' . $row["id"] . '" value="option4">' . $row["option4"] . "</label></div>"];

                    shuffle($options);

                    foreach ($options as $option)
                    {
                        $HTML .= $option;
                    }

                    $HTML .= "</div><br>";
                }
                $HTML .= '<div class="row"><button type="submit" name="submit" value="quiz" class="button">Submit response</button></div></form></div></body>';
            }

        }
        // A submission for a  quiz
        else if ($_POST["submit"] == "quiz")
        {
            $time = time();
            $ip = getUserIpAddr();
            $score = 0;
            $total = count($_POST) - 1;
            
            foreach ($_POST as $key => $value)
            {
                if ($key != "submit")
                {
                    
                    // Grab question data
                     $query9 = mysqli_query($con, "SELECT * from poll WHERE id = '$key'");
                     $row = mysqli_fetch_assoc($query9);
                     
                     $question = $row['question'];
                     $response = $row[$value];
                     $correct = $row['answer'];
                     
                     $max_str_length = 25;
                     
                     
                    // Grab data on all users and the options they chose
                    $chart_data = [];
                       $query4 = mysqli_query($con, "SELECT COUNT(*) AS count FROM responses WHERE value=0 AND poll_id='$key'");
                        $row4 = mysqli_fetch_assoc($query4); // Grab how many of option 0 is present
                        $chart_data[substr($row['answer'], 0, $max_str_length)] = $row4['count'];
                         $query14 = mysqli_query($con, "SELECT COUNT(*) AS count FROM responses WHERE value=1 AND poll_id='$key'");
                        $row14 = mysqli_fetch_assoc($query14); // Grab how many of option 1 is present
                        $chart_data[substr($row['option1'], 0, $max_str_length)] = $row14['count'];
                         $query24 = mysqli_query($con, "SELECT COUNT(*) AS count FROM responses WHERE value=2 AND poll_id='$key'");
                        $row24 = mysqli_fetch_assoc($query24); // Grab how many of option 2 is present
                        $chart_data[substr($row['option2'], 0, $max_str_length)] = $row24['count'];
                         $query34 = mysqli_query($con, "SELECT COUNT(*) AS count FROM responses WHERE value=3 AND poll_id='$key'");
                        $row34 = mysqli_fetch_assoc($query34); // Grab how many of option 3 is present
                        $chart_data[substr($row['option3'], 0, $max_str_length)] = $row34['count'];
                         $query44 = mysqli_query($con, "SELECT COUNT(*) AS count FROM responses WHERE value=4 AND poll_id='$key'");
                        $row44 = mysqli_fetch_assoc($query44); // Grab how many of option 4 is present
                        $chart_data[substr($row['option4'], 0, $max_str_length)] = $row44['count'];
                     
                     

                     if($row['category'] !== "random") {
                     $HTML .= $question . "<br>";
                     $HTML .= "Your answer: " . $response . "<br>";
                     if($response == $correct) {
                         $rand = ["Well done!", "Excellent!", "Good job!", "You did it!", "Superb!", "Great work!", "Keep it up!", "Nice one!", "Good choice!", "Yeargh!", "Sublime!"];
                         shuffle($rand);
                         $HTML .= $rand[0] . " You chose the correct answer.<br><br>";
                     } else {
                     $HTML .= "The answer is: " . $correct . "<br><br>";
                     }
                     }
                     
                    //Convert option to int
                    $response = 0;
                    if ($value != "answer")
                    {
                        $response = (int)filter_var($value, FILTER_SANITIZE_NUMBER_INT);
                        
                    } else {
                    $score += 1;
                    }
                    // We got a poll Id
                    // Add stuff to the table
                    mysqli_query($con, "INSERT INTO responses (time, poll_id, ip, value) VALUES ('$time', '$key', '$ip', '$response')");
                    echo mysqli_error($con);
                    
                    
                    
                       
    $HTML.= '<canvas id=' . str_replace(" ","",$question) .  ' style="width:100%;max-width:300px;max-height:300px"></canvas><br>';
                     
                      $HTML.=  '<script>';
                      $HTML.=  'var data = ' . json_encode($chart_data) . ';'; 
                          $HTML.=  'var  question = "' . $question . '";'; 
                     
                     
     
                     $HTML.= <<< END
    //console.log(nameArr[key].replaceAll(" ", ""));
    var ctx = document.getElementById(question.replaceAll(" ", "")).getContext("2d");
    
    if(typeof x == "undefined") {
    var x = [];
    } else {
        x = [];
    }
    if(typeof y == "undefined") {
        
    var y = [];
    } else {
        y = [];
    }
    
for(var key in data) {
 x.push(key);
 y.push(Number(data[key]));
}

    new Chart(ctx, {
  type: "bar",
  data: {
    labels: x,
    datasets: [{
      data: data
    }]
  },
  options: {
    
    plugins: {
        legend: {display: false},
     
    }
    

  }
});

</script>
END;
                    
                }
            }

            $HTML .= "<div class='box'>Thank you for your responses. You submitted " . count($_POST) - 1 . " responses.</div>"; 
                                 if($row['category'] !== "random") {
                     $HTML .= "You got " . $score . " out of " . $total . " question(s) correct<br>";
                     }
        }
        else
        {
            $HTML .= "<div class='box'>Error...</div>";
        }
    }
    else
    {
        // Show a form for the user to select the type of quiz they want to do
        $query8 = mysqli_query($con, "SELECT DISTINCT category FROM poll");
        //$all = mysqli_fetch_all($query8); *EDIT
        
        $all[0][0] = "random"; // *EDIT
        
        //We got some categories, lets iterate through each one
        $HTML .= '<!DOCTYPE html> <html lang="en"> <head> <meta charset="UTF-8"> <meta name="viewport" content="width=device-width, initial-scale=1.0"> <title>Keval - Kevin Evaluated</title> <link rel="stylesheet" href="style.css">	 </head> <body>';
        $HTML .= '<div class="box"><div class="row">Select the type of quiz you would like to do:</div><form action="" method="post">';
        foreach ($all as $key => $value)
        {
            $HTML .= '<div class="row"><label><input type="radio" name="category" value="' . $value[0] . '" required>' . $value[0] . "</label></div>";
        }
        $HTML .= '<div class="row"><label>Tag: <select id="tag" name="tag"><option value=""></option><option value="concepts">concepts</option><option value="concepts">security</option><option value="concepts">compute</option><option value="concepts">networking</option><option value="concepts">storage</option><option value="concepts">other</option></select></label></div>';
        // *EDIT $HTML .= '<div class="row"><label>Number of questions: <select name="number" id="number" required><option value="1">1</option><option value="5">5</option><option value="10">10</option><option value="40">40</option></select></label></div>';
        $HTML .= '<div class="row"><label>Number of questions: <select name="number" id="number" required><option value="40">40</option></select></label></div>';
        $HTML .= '<div class="row"><button type="submit" name="submit" value="select" class="button">Choose quiz type</button></div></form></div></body>';

    }

    echo $HTML;

?>
