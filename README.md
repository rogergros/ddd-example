# 🎳 Bowling manager 
![Static analysis and tests](https://github.com/rogergros/ddd-example/actions/workflows/ci.yml/badge.svg)

## DDD and Hexagonal Architecture Demo Project

> ℹ️ This is a Demo project. Its goal is to show how a DDD architecture could work with PHP. Because of that,
> functionality is limited and far to be complete.

> 👷 The repository is still under construction. It runs but could be improved a lot. At the end of the file, you'll
> find some improvements that I would like to add at some point.

## 🖥 The project: A bowling manager

I decided to create a bowling manager as a demo project.
Most of us have played bowling at least once and the logic of the game is simple but still complex enough to let me have
fun while showing the possibilities of this architecture.
It is also a well-known TDD Kata so most of us have already played with code to solve the problem.

If you have never played bowling you can check the instruction on the [Bowling TDD Kata project](https://github.com/rogergros/bowling-tdd-kata) on my Github repo.

## 📚 Requirements

To get this project running you only need [🐋 Docker or Docker for Desktop](https://docs.docker.com/get-docker/) installed.

Make sure **docker is running** before running the project.

## 🏃 Running the project

To interact with the project environment I use a Makefile.
You can start the project by opening the project folder on your terminal and running
    
    make start

When the process ends you should be able to access the project on [localhost](http://localhost)

There are other commands that can be useful to interact with the project.
Here's a full list:

|Command|Title|Description|
|---|---|---|
|`make start`|Starts the project|Starts all the containers for the application|
|`make stop`|Stops the project|Stops all the project containers|
|`make php-sh`|Opens an sh on the PHP Container|Executes bash on the PHP container to interact with it|
|`make flush-database`|Clears the database|Removes all project saved data|
|`make check-code`|Runs tests, static analysis and checks code styling|Runs all tests with PHPUnit, PHPStan and Psalm for static analysis and phpcs as code sniffer|
|`make style-fix`|Fixes code styling|PHPcs tries to fix all code styling that can automatically fix|
|`make docker-ease`|Cleans some docker stuff|Removes unused docker data non project related|
|`make docker-prune`|Cleans all docker stuff|Removes unused docker data|

## 🏗 About the architecture

A little guide about the project folders organization.

### 📁 Src folder

The src folder contains all business logic it is distributed into four folders.
Three of them map the three layers I use to separate our application on DDD: **Application**, **Domain**, and **Infrastructure**

There's a fourth folder that contains shared objects that are used on the entire project.
Mainly Value Objects and Exceptions.

#### 🔴 Application

Contains all the entry points to interact with our domain.
I use Commands to send orders to the application and queries to gather data from it.
These are usually related to our application test cases.
A command and event bus is used to call the commands and queries handlers logic.

#### 🟠 Domain

The domain is the heart of the app. It contains the entire business logic and describes our application.
It should not contain any reference to anything outside our domain.
It also contains the interfaces to interact with our repositories as I use the Dependency Inversion principle to avoid
coupling with our infrastructure.

#### 🟢 Infrastructure

The infrastructure code is responsible to connect our project with infrastructure-related stuff.
Here you'll find all the real repositories that save the data and tools that I use to connect the pieces of our app and
that are probably coupled to external dependencies: Framework, libraries, etc.

### 📁 App folder

The App folder contains the entry points of our app. These are coupled to the framework as this manages our entry points
(Controllers, commands, etc.).
It also contains app-related files.
Right now it contains the controllers for the web app and an API endpoint as an example of how easy is to just reuse
use cases on multiple scenarios (Web app, API, Command line, WebHook...)

### 📁 Other folders

There are other folders that contain mainly symfony related stuff, infrastructure code to run the app, libraries, caches, etc.

## CI

The project uses [Github Actions](https://github.com/features/actions) to run a CI process that ensures that every commit on the main branch:
* Passes all the tests ([PHPUnit](https://phpunit.de/))
* Static code checking is green ([PHPStan](https://phpstan.org/) and [Psalm](https://psalm.dev/))
* Code styling is right ([PHP Code Sniffer](https://github.com/squizlabs/PHP_CodeSniffer))

You can see the CI workflows results [here](https://github.com/rogergros/ddd-example/actions)

## 🛠 Possible improvements

These are improvements that I would like to add at some point:

* Functionalities
  * Restart game on lane
  * Update Bowling alley data
  * Show only valid buttons to update game (Now there are always 10 buttons even when some pins are down)
  * Show partial score when a frame has spare or strike but extra rolls have not been done yet
* Repository limits and pagination
* More tests. I did some TDD to build our domain but Handlers and other classes should be also tested.
* Some domain logic could be moved from primitives to value objects (e.g. GameLane), I just simplified for faster development
* Use a ORM to store entities into a real DB. I've used disk storage for faster development.
* Queries could directly run on the DB and build views without coupling to the domain. I've tried to isolate them on the repos but stil don't like to couple that way
* Use criteria pattern for simple repositories. They should only contain `save`, `byId` and `search` methods.
* Use a search engine for faster queries (Elasticsearch?)
* Capture exceptions and convert them into proper HTML code errors

## 🔝 Other improvements

There are some other improvements that are out of this demo but that I would probably apply on real production projects:

* Projections: Use events from the domain to create projections of our data ready to be read using for example Redis.
* Search engine: Use a search engine for powerful search
* Events: Use events to communicate between modules
