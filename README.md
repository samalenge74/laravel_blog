

## Laravel Blog

This blog was developed as an assessment of my development skills and experience for a job application. It took about 2 working day to develop, test, debug and fix. 

This laravel web application has the following:

- User can login and/or register 
- Registered users are by default authors and can create new blogs
- All blogs are listed on the landing page in groups of five and can be viewed by all
- Only logged in users can post comments and/or rate a blog. For the sake of the assessment, the value of the average rating is rounded down into an interger
- Only the author of the blog can edit and/or delete a blog
- Registered users have also the option of view the list of all their blogs only

## How to install this application on your server/pc

- Make sure that you have Apache, MySQL and PHP installed on your machine - the latest version preferrably
- Create a MySQL database and assigned a user to it
- Clone this project into a specific folder in your web directory. You will need to have Git installed in your machine to clone the project
- Open the .env file and update the necessary configuration changes such as application name and database information.
- From the command line, being in the project folder, execute database migation abd seeder
