<?php

// This is a script that is run during the installation process
// It will run some SQL code
// Delete this file once installation is complete

include("config.php"); //Includes connection to the database

echo "<b>Installation of databases via MySQL / MariaDB</b><br>";

// Poll questions
// Create a table that stores all poll questions
// Poll questions have the following properties:
// An ID (auto-increment)
// A question
// Image URL
// 5 options. The first option is the correct 'answer'
// A category
// Creator's IP
// Creator's name

$sql="
CREATE TABLE `poll` (
  `id` int(11) NOT NULL auto_increment,
  `time` int(255) NOT NULL,
  `question` text collate latin1_general_ci NOT NULL,
  `image_url` varchar(512) collate latin1_general_ci,
  `answer`  varchar(512) collate latin1_general_ci NOT NULL,
  `option1`  varchar(512) collate latin1_general_ci NOT NULL,
  `option2`  varchar(512) collate latin1_general_ci NOT NULL,
  `option3`  varchar(512) collate latin1_general_ci NOT NULL,
  `option4`  varchar(512) collate latin1_general_ci NOT NULL,
  `category` varchar(255) collate latin1_general_ci default 'general',
  `tag` varchar(255) collate latin1_general_ci default '',
  `ip` varchar(255) collate latin1_general_ci,
  `creator` varchar(255) collate latin1_general_ci default 'Anonymous',
  PRIMARY KEY  (`id`)
);";

if ($con->query($sql) === TRUE) {
    echo "<br>Poll table created.";
} else {
    echo "<br>Error creating table: " . $con->error;
}


// Upvotes and downvotes
// A table that stores a list of 'upvotes and downvotes'
// Upvote or downvote is based on ID and user's IP
$sql="
CREATE TABLE `votes` (
  `id` int(11) NOT NULL auto_increment,
  `time` int(255) NOT NULL,
  `poll_id` int(11),
  `ip` varchar(255) collate latin1_general_ci,
  `value` tinyint(255),
  PRIMARY KEY  (`id`)
);";

if ($con->query($sql) === TRUE) {
    echo "<br>Votes table created.";
} else {
    echo "<br>Error creating table: " . $con->error;
}


// Answers
// A table that stores a list of submitted responses
$sql="
CREATE TABLE `responses` (
  `id` int(11) NOT NULL auto_increment,
  `time` int(255) NOT NULL,
  `poll_id` int(11),
  `ip` varchar(255) collate latin1_general_ci,
  `value` tinyint(255),
  PRIMARY KEY  (`id`)
);";

if ($con->query($sql) === TRUE) {
    echo "<br>Responses table created.";
} else {
    echo "<br>Error creating table: " . $con->error;
}


echo "<br><br><b>Delete this php file once installation has been completed</b>";

?>
