# DDD Example Project

![Static analysis and tests](https://github.com/rogergros/ddd-example/actions/workflows/ci.yml/badge.svg)

This project is an example of DDD architecture using PHP. This is far to be perfect, but it's a good approach to see
some DDD best practices.

## Using the project

This project has a Makefile that can be used to use it. Some commands are:

|Command|Title|Description|
|---|---|---|
|`make start`|Starts/runs the project|Starts all the containers for the application|
|`make start-dev`|Starts/runs the project for development|Starts all the containers for the application with the folder as a volume|
|`make stop`|Stops the project|Stops all the project containers|
|`php sh`|Opens an sh on the PHP Container|Executes bash on the PHP container to interact with it|

## Possible improvements

* Some domain logic could be moved from primitives to value objects (simplified for faster development)
* Do not expose domain entities when querying, instead of that transform them into views
* Use a ORM to store entities into a real DB
* Use the criteria pattern on repository searches and use a search engine (Elasticsearch?)
