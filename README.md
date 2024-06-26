# online-poll
A PHP / MySQL / Chart.js framework for creating web polls / quizzes

[Live Demo](https://kevinteong.duckdns.org)

![Front page](https://github.com/boomyville/online-poll/blob/main/pic1.png?raw=true)

# Why
As part of my bootcamp, each student was given the opportunity to do a 'daily opening'.

Most of the daily openings were either playing games (such as mojiparty.io or scribble.io) or chatting (share a story or a favourite thing)

But I wanted to host a game show. Family Feud crossed my mind

But there was no framework to gather responses from participants and the polls available were clunky to administer

So I decided to create my own poll framework to facilitate this. I used PHP / MySQL because I've used it before for projects and despite PHP being a dying language its still useful for facilitating these type of tasks. Users were given a form they could fill out to submit questions and then a separate page is used for participants to input their data. Another page uses chart.js to display the results (how often an option was selected). This was then used to facilitate the game show where participants would be asked which option they thought was picked the most. As usual, there were some interesting results but for the group in general it was a good way to start conversations about various topics (like what is the best chocolate bar) and match people with similar interests.

Afterwards, I redesigned the project to allow users to submit exam questions to a 'bank' which could then be used by others as a way to practice for certification exams. This is why there is an 'answer' option for polls. Again, chart.js is used to show which options was selected by participants as well as the correct answer. When users conducted a quiz, the system will show questions they have not responded to first (randomly) and if the user had exhausted all unseen questions then random questions will be shown.

I was planning to add a 👍 / 👎 component to questions where users can up vote or down vote questions and questions that were voted more frequently were more likely to appear in quizzes and down voted ones would be flagged for moderations and appear less frequently.

# Installation
Make sure you have a MySQL server setup as well as a LAMP stack (We need PHP baby!)

Change config.php and include your MySQL server login details

Upload to www folder of your web server

Run install.php in your browser. Delete after installation.

Use [adminer.php](https://www.adminer.org/) to play around with database. Use [tinyfilemanager.php](https://tinyfilemanager.github.io/) to remotely access files

![Front page](https://github.com/boomyville/online-poll/blob/main/pic2.png?raw=true)
