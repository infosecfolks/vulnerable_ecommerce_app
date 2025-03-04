# Vulnerable_ecommerce_app
This vulnerable e-commerce application is built in a Docker environment using Apache, PHP, and MySQL technologies. It is intended solely for educational purposes to practice penetration testing. Do not attempt these techniques on real-world applications without proper consent from the application owners.

Default username & password 
----------------------------
username - infosecfolks <br>
password - infosecfolks@123

![Architecture](vulnerable%20ecommerce%20architecture.jpg)

Follow the below steps to deploy it as docker in your machine.

> [!TIP]
> We recommend using Kali Linux as your base OS for running these Docker containers, as it comes pre-installed with the essential tools for learning penetration testing! 

## Steps to Install and enable docker

> [!NOTE]
> If you don't have docker installted in your machine you can follow this below steps, otherwise skip to Building and running docker containers

Update kali repository <br> 
**#sudo apt-get update**

Installing docker <br> 
**#sudo apt-get install -y docker.io**

Start and enable docker service<br> 
**#sudo systemctl start docker <br> 
#sudo systemctl enable docker**

Check docker version <br> 
**#docker --version**

## Steps to run vulnerable ecommerce as docker container 

After installing docker, now navigate to the cloned directory which is "vulnerable_ecommerce_app" and execute following docker commands <br> 
to deploy and launch vulnerable ecommerce application

This command build the web container <br> 
**#sudo docker build -t ecommerce_web .**

This command build and run the database container <br> 
**#sudo docker run -d --name ecommerce_db -e MYSQL_ROOT_PASSWORD=password -e MYSQL_DATABASE=vuln_ecommerce mysql:5.7**

This command runs the web container and link with database container <br> 
**#sudo docker run -d --name ecommerce_web -p 8090:80 --link ecommerce_db:db -v $(pwd)/app:/var/www/html ecommerce_web**

This command initialize the database <br> 
**#sudo docker exec -i ecommerce_db mysql -u root -ppassword vuln_ecommerce < app/db_init.sql**

Set auto-restart (Containers automatically restart after poweroff/reboot of kali linux
**#sudo docker update --restart unless-stopped ecommerce_web** <br>
**#sudo docker update --restart unless-stopped ecommerce_db**


Follow us for more updates <br> 
[INFOSEC FOLKS - LINKEDIN](https://www.linkedin.com/company/infosecfolks-global/) <br> 
[INFOSEC FOLKS - LINKEDIN](https://www.linkedin.com/company/infosecfolks-telugu) <br> 
[INFOSEC FOLKS - YOUTUBE](https://www.youtube.com/@infosecfolks-global/) <br> 
[INFOSEC FOLKS Telugu - YOUTUBE](https://www.youtube.com/@InfosecFolks-Telugu/) <br>

