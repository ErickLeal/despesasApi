# Expenses Api

**About docker** <br>
Docker-compose.yml are available in the projetc: Laravel, MariaDb and Mailhog are configured. <br>
To set the entire environment, just run <br>
`docker compose up -d` <br>

**About email notifications** <br>
Laravel queues were used so that emails are dispatched asynchronously. 
To start the queue worker just run inside the container:
`php artisan queue:work --queue=notifications` <br>

As this is an application in a development environment, a local email server was used for testing. <br>
To check all sent emails, simply open: <br>
`http://localhost:8025/` <br>

## Postman Documentation
Postman collection files are avaiable on the repository, to use just import in postman. <br>

*User documentation*
https://documenter.getpostman.com/view/10787241/2s9YJc1Naz

*Expenses documentation*
https://documenter.getpostman.com/view/10787241/2s9YJc1Nb1

