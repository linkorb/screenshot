Screenshot generator
====================

## Starting Selenium

    wget http://selenium-release.storage.googleapis.com/2.44/selenium-server-standalone-2.44.0.jar
    java -jar selenium-server-standalone-2.44.0.jar
    
## Installing chrome driver:

    wget https://sites.google.com/a/chromium.org/chromedriver/
    
    java -jar selenium-server-standalone-2.44.0.jar -Dwebdriver.chrome.driver=~/Downloads/chromedriver
    
## Running a Screenshot Script

    ./bin/screenshot screenshot:run myscript.json
