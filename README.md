# BlocklistBlaster - Made for PiHole
BlocklistBlaster is a web tool that allows you to combine multiple blocklists into one, removing duplicate entries and providing a single, consolidated list. With BlocklistBlaster, you can easily create and maintain your own custom pihole blocklist to enhance your pihole performance.

### What this tool realy does:
Redundant entries in multiple blocklists can slow down the performance of a PiHole, especially on smaller systems. This tool aims to help alleviate that burden by allowing users to create a consolidated list of unique entries from multiple blocklists. By removing duplicates, the resulting list is smaller and faster to process, which can help smaller PiHole systems run more efficiently.

### Requirements:
- Webserver running php 8.0 or higher
- MySQL server running 8.0.32 or higher
- PiHole

### Installation & usage:
1. Copy the necessary PHP files onto a web server that has PHP 8.0 or higher installed.
2. Open your browser and navigate to the setup.php file and enter the connection details for your MySQL server.
3. Click on the "Save" button and then navigate to the create.php file. You can enter all the PiHole blocklists urls separated by commas.
4. Once you save the blocklists, you can view the completed list by clicking on the "view" button.
5. The link to the list will remain the same.
6. If you need to make any changes in the future, you can do so by going to /create.php?id=XXXXX.

### Please keep in mind:
This PHP tool is not secure enough to run on public instances due to security vulnerabilities. It is important to ensure that the script has proper input validation and error handling to prevent malicious attacks such as SQL injection or cross-site scripting (XSS). Additionally, it is recommended to use the latest version of PHP and keep the script updated with security patches. Failure to do so could leave your application open to potential security risks and compromise sensitive data.
