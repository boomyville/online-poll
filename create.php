<?php
// This is will create a poll question
include ("config.php"); //Includes connection to the database
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Create poll option (using forms)
// We store the form HTML here
$formHTML = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add a question</title>
    <link rel="stylesheet" href="style.css">	
</head>
	<body>
		<div class="box">
			<div class="question">
				<p>Please add a question with 5 different answers</p>
			</div>
			<form method="POST" action="">
			<div class="question">
				<div class="row">
					<input type="text" class="form" id="question" name="question" placeholder="Question" required>
				</div>				
                <div class="row">
					<input type="text" class="form" id="image_url" name="image_url" value="Image URL" disabled>
				</div>
				<div class="row">
					<input type="text" class="form" id="answer" name="answer" placeholder="Answer" required>
				</div>
				<div class="row">
					<input type="text" class="form" id="option1" name="option1" placeholder="Alternative response 2" required>
				</div>		
				<div class="row">
					<input type="text" class="form" id="option2" name="option2" placeholder="Alternative response 3" required>
				</div>
				<div class="row">
					<input type="text" class="form" id="option3" name="option3" placeholder="Alternative response 4" required>
				</div>
				<div class="row">
					<input type="text" class="form" id="option4" name="option4" placeholder="Alternative response 5" required>
				</div>		
                <div class="row">
                    <select id="category" name="category">
                    <option value="AZ-900" disabled>AZ-900</option>
                    <option value="AZ-104" disabled>AZ-104</option>
                    <option value="Random">Random</option>
                    </select>
				</div>				
				<div class="row">
                    <select id="tag" name="tag">
                    <option value=""></option>
                    <option value="concepts" disabled>concepts</option>
                    <option value="security" disabled>security</option>
                    <option value="compute" disabled>compute</option>
                    <option value="networking" disabled>networking</option>
                    <option value="storage" disabled>storage</option>
                    <option value="governance" disabled>governance</option>
                    </select>
				</div>			
                <div class="row">
					<input type="text" class="form" id="creator" name="creator" value="
HTML;


$ip = getUserIpAddr();
$ya = mysqli_query($con, "SELECT * FROM poll WHERE ip = '$ip' ORDER BY ID DESC LIMIT 1");
if (mysqli_num_rows($ya) > 0)
{
    $ya2 = mysqli_fetch_row($ya);
}
else
{
    $ya2[12] = "Anonymous";
}
$formHTML .= $ya2[12];

$formHTML .= <<<HTML
" required>
				</div>		
                <div class="row">
                    <button type="submit" name="submit" value="Submit" class="button">Submit question</button>
				</div>
			</div>
		</div>
    </body>
</html>
HTML;


// Poll questions have the following properties:
// An ID (auto-increment)
// A question
// Image URL
// 5 options. The first option is the correct 'answer'
// A category
// Creator's IP
// Creator's name
if (isset($_POST['submit']))
{
    // Sanitise input; no SQL injections please
    $question = mysqli_real_escape_string($con, stripslashes($_POST['question']));
    if (array_key_exists('image_url', $_POST))
    {
        if ($_POST['image_url'] == "Image URL")
        {
            $image_url = ""; //No image url
            
        }
        else
        {
            $image_url = mysqli_real_escape_string($con, stripslashes($_POST['image_url']));
        }

    }
    else
    {
        $image_url = ""; //No image url
        
    }
    $answer = mysqli_real_escape_string($con, stripslashes($_POST['answer']));
    $option1 = mysqli_real_escape_string($con, stripslashes($_POST['option1']));
    $option2 = mysqli_real_escape_string($con, stripslashes($_POST['option2']));
    $option3 = mysqli_real_escape_string($con, stripslashes($_POST['option3']));
    $option4 = mysqli_real_escape_string($con, stripslashes($_POST['option4']));
    $category = mysqli_real_escape_string($con, stripslashes($_POST['category']));
    $tag = mysqli_real_escape_string($con, stripslashes($_POST['tag']));
    $creator = mysqli_real_escape_string($con, stripslashes($_POST['creator']));
    $time = time();

    $ip = getUserIpAddr();
    
    //Check default stuff
    if(str_contains($answer, "Answer") || str_contains($option1, "Alternative response") || str_contains($option2, "Alternative response") || str_contains($option3, "Alternative response") || str_contains($option4, "Alternative response")) {
        echo "Please insert a non-default response.";
    } else {
    
    // Add stuff to the table
    mysqli_query($con, "INSERT INTO poll (question, time, image_url, answer, option1, option2, option3, option4, category, tag, ip, creator) VALUES ('$question', $time, '$image_url', '$answer', '$option1','$option2', '$option3', '$option4', '$category', '$tag', '$ip', '$creator')");
    echo mysqli_error($con);
    echo "The question of: " . $question . " has been added to the " . $category . " database. Thank you " . $creator . "!";
    }
}
else
{
    echo $formHTML;
}

?>
