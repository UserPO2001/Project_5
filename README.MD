# Energy Consumption Tracker

This application is a PHP-based web tool that allows users to monitor and track their electricity and water usage, offering personalized tips based on consumption levels. It also includes a login system, a MySQL database managed with phpMyAdmin, and JavaScript animations for a typewriter effect. The steps below guide you through setting up the environment and running the application with **XAMPP**.

## Prerequisites

You will need **XAMPP**, a popular free software stack that includes Apache, MySQL, PHP, and phpMyAdmin, which lets you host your PHP-based applications on your local machine.

## 1. Downloading and Installing XAMPP

1. **Download XAMPP**  
   Head over to the [official XAMPP website](https://www.apachefriends.org/index.html) and download the installer for your operating system.

2. **Install XAMPP**  
   Run the downloaded installer and follow the on-screen instructions. During installation, make sure you include Apache and MySQL. 

3. **Launch XAMPP Control Panel**  
   Open the XAMPP Control Panel after installation. You’ll see a list of modules including Apache and MySQL. Start both by clicking the **Start** buttons next to each.

## 2. Setting Up the Application in XAMPP

1. **Copy Files to the `htdocs` Directory**  
   Locate your XAMPP installation folder (typically `C:\xampp` on Windows or `/Applications/XAMPP` on Mac). Inside, open the `htdocs` directory, which is the root folder for all your web applications.

2. **Place Application Files in `htdocs`**  
   Copy your project files (e.g., `index.php`, `login.php`, and any other files) into a subfolder inside `htdocs`. For example, you might place them in a folder called `EnergyTracker`, so your file path would look like `C:\xampp\htdocs\EnergyTracker`.

3. **Launch XAMPP and Access Your Application**  
   Make sure both Apache and MySQL are still running in the XAMPP Control Panel. Open a browser and go to:  
   `http://localhost/Your_Folder_Name`  
   For example, `http://localhost/EnergyTracker`.

## 3. Setting Up the Database with phpMyAdmin

**phpMyAdmin** is a tool that lets you easily manage your MySQL databases from a web interface.

1. **Access phpMyAdmin**  
   In your browser, go to `http://localhost/phpmyadmin`. This will open the phpMyAdmin interface.

2. **Create a New Database**  
   - Click on **New** in the sidebar.
   - Enter a name for your database (e.g., `energy_tracker`) and select **Collation** for the database type.
   - Click **Create**.

3. **Set Up Database Tables**  
   - Click on the database you created (e.g., `energy_tracker`).
   - Go to the **SQL** tab.
   - Paste your table setup SQL code (queries) in the text area. Click **Go** to execute.

4. **Connecting PHP to MySQL**  
   - In your PHP files, make sure you have the correct MySQL connection details, usually in a configuration file. 
   - Example connection code:

     ```php
     $servername = "localhost";
     $username = "root";
     $password = "";
     $dbname = "energy_tracker";

     $conn = new mysqli($servername, $username, $password, $dbname);

     if ($conn->connect_error) {
         die("Connection failed: " . $conn->connect_error);
     }
     ```

5. **Testing the Application**  
   Once the database and PHP files are set up, revisit `http://localhost/Your_Folder_Name` in your browser to test the application.

## 4. Additional Notes

- **Typewriter Animation**: This application includes a JavaScript typewriter animation. Make sure to include the `.js` file and link it in your HTML or PHP files where necessary.
  
- **Common Ports**: By default, XAMPP runs on **port 80**. If you have other services on port 80, you may need to change Apache's port in the XAMPP Control Panel (Config > Apache > httpd.conf).

Happy tracking! If you encounter issues, check your XAMPP Control Panel for any errors, particularly with Apache or MySQL.
