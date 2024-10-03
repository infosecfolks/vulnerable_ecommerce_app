# vulnerable_ecommerce_app
This is vulnerable ecommerce application built on docker environment using PHP and Mysql technologies.

Follow the below steps to deploy it as docker in your machine.

> [!TIP]
> We recommend to use Kali linux as your base machine to run this docker containers, as it is already pack with required tools for learning penetration testing. 

## Installing and enabling docker

> [!NOTE]
> If you don't have docker installted in your machine you can follow this below steps, otherwise skip to Building and running docker containers

Update kali repository 
**#sudo apt-get update**

Installing docker 
**#sudo apt-get install -y docker.io**

To start and enable docker service
**#sudo systemctl start docker
#sudo systemctl enable docker**

Check docker version
**#docker --version**

## Deploying and running vulnerable commerce as docker container

After installing docker, now navigate to the cloned directory which is "vulnerable_ecommerce_app" and execute following docker commands 
to deploy and launch vulnerable ecommerce application

This command build the web container 
**#sudo docker build -t ecommerce_web .**

This command build and run the database container
**#sudo docker run -d --name ecommerce_db -e MYSQL_ROOT_PASSWORD=password -e MYSQL_DATABASE=vuln_ecommerce mysql:5.7**

This command runs the web container and link with database container
**#sudo docker run -d --name ecommerce_web -p 8090:80 --link ecommerce_db:db -v $(pwd)/app:/var/www/html ecommerce_web**

This command initialize the database
**#sudo docker exec -i ecommerce_db mysql -u root -ppassword vuln_ecommerce < app/db_init.sql**

Follow us for more updates 
[INFOSEC FOLKS - LINKEDIN](https://www.linkedin.com/company/infosecfolks-global/)
[INFOSEC FOLKS - YOUTUBE](https://www.youtube.com/@infosecfolks-global/)
