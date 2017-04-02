# Unreal API Backend Webservice - (for Reuben Ward's Unreal MySQL tutorial)

The following is an example for an API Backend Webservice for Reuben Wards Patreon Only Unreal Lectures integrating MySQL with Unreal.

This webservice is based on Lumen by Laravel. This was chosen to provide a proper api with authentication implementation. We are using JWT for our authentication.

## How to get setup

Just follow the steps below to get the system setup. We will cover very simple integration of NGINX, MySQL, PHP-FPM all running locally on a windows machine. 

It is assumed that if you are running on any Linux distribution you are familiar with the concept of setting up a local webserver incl. mysql and php. If not please take a look at google for your Linux distribution.
 
 Let's get started. 
 
1. Download and install GIT for Windows. Download from [HERE](https://git-scm.com/downloads). Make sure to download the x64 version for your operating system. 
    1. When installing make sure to install the extension in your explorer so that you may right click from windows context menu

2. Next we are going to install MySQL Community Edition. You can get the latest version [here](https://dev.mysql.com/downloads/installer/). Just install it with default options but chose a nice password for your root account when configuring. It is important that you remember this password as we will need it soon!
    1. As a note I highly recommend getting a MySQL tool like Navicat that will allow you to see your MySQL DB in a nice wrapped GUI. It is not required but would be of great help down the line. There are several free options as well, google is your friend. 
    2. A good free example would be MySQL Workbench from the developers of MySQL or HeidiSQL etc. The choice is yours. [Take a look](https://www.google.ie/search?q=free+mysql+gui+windows&gws_rd=cr&ei=XuzgWMDIM6iTgAadxoDwAQ#safe=off&q=free+mysql+client+windows)
    3. Next we need to quickly setup the database for our project. I am going to call mine ``` unreal_webapi ```. Open your MySQL Client (whichever you chose) and create a new connection to your DB on your own machine. Typical connection parameters are as follows:
     ```
     host=localhost OR 127.0.0.1
     port=3306
     username=root
     password=<the password you chose at install/config>    
     ```
     Then create a database and call it what you like. This can be any string.

3. Next install Composer, this is required to pull in dependencies for our webapi project. You can download the Windows Installer for Composer [here](https://getcomposer.org/Composer-Setup.exe).

4. Once everything is installed you want to create the following folders on your machine. These are required, but with enough knowledge you can change them later on. 
    ```
    C:\nginx  --- this is where our webserver will reside
    C:\www\unreal_wepapi --- this is where the web application will be
    ```

5. Now we are going to clone the GIT repository into the unreal_webapi folder
    1. If you have followed the above instructions properly you should be able to right click inside the c:\www\unreal_webapi folder and chose GIT Bash here. This should open a new terminal like window that will allow us to use a more native UNIX approach to things, with things like SSH etc.
    2. Once that window is open you need to run the following command:
    ```
    git clone git@github.com:Megachill/unreal_webapi.git .
    ```
    The <strong>.</strong> at the end is super important as otherwise git will clone into a new directory. Once cloned make sure you see the following folder structure. If you have this you are good and move onto step 6
    ```
    /app
    /bootstrap
    /config
    /database
    /public
    /resources
    /routes
    /setup_files
    /storage
    /tests
    .env
    .gitignore
    artisan
    composer.json
    composer.lock
    phpinfo.php
    phpunit.xml
    readme.md
    ```

6. With the terminal (GIT Bash) window still open we now need to run composer to setup dependencies. This is important as otherwise your application will not work. Run the following commands in order:
 ```
 composer self-update
 composer install
 ```
    
    1. Open the .env file with any text editor of your choice and edit the following entries within:
        ```
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=<the database name>
        DB_USERNAME=<the user you created when configuring MySQL, normally root>
        DB_PASSWORD=<the password you gave that username>
        ```
    2. Save the .env file and close it
    
    3. Now for migrating our database with relevant structure. Run the following command from the folder where your webapp is in. (DEFAULT is ``` c:\www\unreal_webapi ``` )
    ```
    php artisan migrate
    ```
    
    4. Now we put some data into the users table. Run the following command from the folder where your webapp is.
    ```
    php artisan db:seed
    ```
    
    5. data has now been filled into our users table and we are good to go to test 

7. Now that we have installed dependencies and configured, we can extract the webserver into our ``` c:\nginx ``` folder. Open the ZIP file inside of ``` /setup_files ``` folder. Extract the contents of this file INTO the earlier created ``` c:\nginx ``` folder. 

8. Once all files are extracted we should now be able to run our webserver by running ``` c:\nginx\start_nginx.bat ``` this should start the webserver. When prompted to allow network access, say yes.
    1. when you run the webserver you will see a DOS like window popup, this will stay open just minimize and do not worry about it. It just means that PHP is running properly.
    2. You can test that the webserver is running properly by going to the following URL
    ```
    http://localhost
    ```
    You should see a page with the following.
    ```
    ["Hello Unreal."]
    ```
    If you do not then something went wrong. It is usually a good point to look at ``` c:\nginx\logs ``` there you will find an error.log file with possibly further details. If you do not then please contact Megachill over on the Patreon forums and I will try and help. 

9. This is it. You should now be able to use the system as described below. As one last step I highly recommend installing POSTMAN to be able to test the system. [Chrome Webstore URL](https://chrome.google.com/webstore/detail/postman/fhbjgbiflinjbdggehcddcbncdddomop?hl=en) 
 
## Routes (registration, login, logout)

### register a new user
```
http://localhost/auth/register
```
 In order to register a new user on the above route, you need to provide form-data in json and make a <strong>POST</strong> request as follows: required keys are:
 ```
 ===HEADERS===
 key:Accept     value:application/json
 ===BODY===
 key:username
 key:email
 key:password
 ```
 once successfully registered the webservice will reply with the following:
 ```json
 {
   "data": {
     "username": "<username>",
     "email": "<users email address>",
     "updated_at": "DATETIME STAMP",
     "created_at": "DATETIME STAMP",
     "id": <whatever this users ID is>
   },
   "meta": {
     "token": "THE TOKEN WE NEED FOR LOGGING IN."
   }
 }
 ```
 
 if there were any errors encountered the system will tell us, like this:
 ```json
 {
   "username": [
     "The username has already been taken."
   ],
   "email": [
     "The email has already been taken."
   ]
 }
 ```
 
 
 
### login 
 
 Make a POST request to the following URL (please note the Bearer part this is important!)

 ```
 ===HEADERS===
 key:Accept    value:application/json
 ===BODY=== (form-data)
 key:username       value:<the username>
 key:password       value:<the password>
 ```
 
 On successful login we will get a response just like when we registered the user, like so:
 
 ```json
 {
    "data": {
      "username": "<username>",
      "email": "<users email address>",
      "updated_at": "DATETIME STAMP",
      "created_at": "DATETIME STAMP",
      "id": <whatever this users ID is>
    },
    "meta": {
      "token": "THE TOKEN WE NEED FOR LOGGING IN."
    }
  }
 ```
 
### logout

 Make a POST request with the users token to the following URL:
 
 ```
 ===HEADERS===
 key:Authorization value:Bearer <token we got from login / register>

 URL: http://localhost/auth/logout
 ```
 
 if successful we should receive a response like so: 
 
 ```json
 {"message": "logout_success"}
 ```

## Final note
Please keep in mind that this system is by no means perfect nor is it feature complete. But this should give you a good idea on how api authentication works in the real world.
 
## FAQ
TODO

## License

[MIT license](http://opensource.org/licenses/MIT)
