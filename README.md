#RESTful JSON Challenge

### Installation using Docker

This application has been Dockerized so there is no need to go about fishing for resources. The commands below assume 
you are running a linux system including macOS.

To get this working, you will need:

- docker
- docker-compose

You can run the docker either as a native application or virtualized via VirtualBox.

- Deploy the docker system by running `docker-compose up -d`
    - If you get an error, you may need to initialize your docker box. Run 
    `docker-machine start`.
    - Follow the onscreen instructions after that.
    
- Run the migrations by running `docker exec -it restful-json_php_1 php artisan migrate`
- The application should be ready now on port `8090`. 
If that port is already in use, your may need to set a new port. Just update the `docker-compose.yml` file
and run the deploy command again.

### Testing
- To test the application run `docker exec -it restful-json_php_1 ./vendor/phpunit/phpunit/phpunit tests`

### Considerations

- Authentication is very insecure. An Oauth2 implementation would provide more security.