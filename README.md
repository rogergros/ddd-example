# DDD Example Project

This project is an example of DDD architecture using PHP.
This is far to be perfect, but it's a good approach to see some DDD best practises.

## Using the project

This project has a Makefile that can be used to use it. Some commands are:

|Command|Title|Description|
|---|---|---|
|`make start`|Starts/runs the project|Starts all the containers for the application|
|`make start-dev`|Starts/runs the project for development|Starts all the containers for the application with the folder as a volume|
|`make stop`|Stops the project|Stops all the project containers|
|`php sh`|Opens an sh on the PHP Container|Executes bash on the PHP container to interact with it|