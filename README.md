# Sweetwater Code Test
I pulled down the Laravel repo and built my solution on top of it, so apologies for the large number of commits.

The link to the report is /comments, from the application root.

A running example of this report is up on a Digital Ocean droplet, located here: http://159.89.81.69/comments

## Implementation
### Task 1 - Write a report that will display the comments from the table
I opted to use a SQL view for categorizing the data; it does a 'like' comparison on a lowercased string and assigns one of five categories based on what it finds first, if anything. The SQL for this can be found in the migrations folder in the final migration, create_comments_report_view.

The CommentsReportController queries the view five times on page load; once for each section on the page, pulling a single page of data to render for each. I used Laravel's built in paginator for this. Clicking through to another page rerenders the entire page and requeries for the current page on the unaffected tables, and I'm not terribly happy about it, but the assignment said to work quickly and I'm okay sacrificing the kind of quality here that I would ship.

Make it work, then make it nice, right? I did add a few polish tweaks to make it not-completely-annoying to use.

### Task 2- Populate the shipdate_expected field in this table with the date found in the `comments` field (where applicable)
This was implemented with a stored procedure, the SQL for which is in the migrations folder in the second-to-last migration, update_comments_shipdate_expected_procedure.

This query updates the table one time only for records with a zero shipdate_expected, if it finds Expected Ship Date in the comment. I did it this way so that I'm not constantly overwriting this field with the same value over and over again. I don't know if comments can change -- it would not be difficult to update this sproc if that requirement was present, to change the date if the date parsed from the comment does not match the date in the column.

I also had to make an odd preface to this query. Newer versions of MySQL do not allow zero dates and I was having trouble working with this. Rather than figure out how to configure the DB to permanently allow this, I found a solution that temporarily disables zero dates for the active session. The sproc turns this flag off before running.

## Local Setup
This is my first exposure to Docker and I largely followed this guide to get my containers up and going: https://www.digitalocean.com/community/tutorials/how-to-set-up-laravel-nginx-and-mysql-with-docker-compose-on-ubuntu-20-04

There's a Dockerfile and docker-compose.yml in the project that defines three containers (one for nginx, one for MySQL, and one for the PHP app) and has bind-links setup to allow the PHP code and the database to persist outside of the containers.

After raising the containers, I followed the rest of the guide for configuring the application key for Laravel and for creating the MySQL user.

I wasn't sure how to script out seeding the data. I did this part by hand using MySQL Workbench on my Windows box, and I had to use that preface in the sproc to disable the zero date settings and allow the migration to succeed.
