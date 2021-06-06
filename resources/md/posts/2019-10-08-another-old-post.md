@title Results will be generated inside the build directory
@author Hugo Blanco
@date 2019-10-08
@summary Some quick example text to build on the card title and make up the bulk of the card's content.
@published true

## Design choices

### Language

Even with the disparity of resources between Clojure and PHP, I chose the language because I could more quickly demonstrate my knowledge. Some issues I needed to solve were created mainly by the lack of immutable structures that Clojure has, but PHP is the language I've worked the longest in my career.


### Structure

In this project, I separated code into packages, trying hard to follow a structure close to DDD. Each package has its own context and tests.
The modular application, at least in PHP, is one way I've found to separate contexts and make code search easier.


### Immutability

Because it is an object-oriented application, some points are virtually impossible to adopt immutability.
I implemented as much of the concept as I could, using `DateTimeImmutable` structures, encapsulation, *value-objects*, and id arguments to avoid object reference problems.


### Dependency injection

I used dependency injection with autowiring to reduce coupling problems and verbose application configuration.


### Core

I tried to create a core robust enough to handle the current problem and new domain packages. In just about every project I've worked on, having a foundation that can allow for timely modifications has proven to be a great ally for productivity.
I developed action routing thinking about the ease of adding new features in the future.
With the middleware structure in place, you can change the input or output format from `json` to `xml` or `csv` with a few lines of code.


### Validations

In this part I was more succinct to not make the handler code too complex and too costly to test, but in a real application I always try to be very careful with the input handling.


### New attributes

I took the liberty of adding a bool attribute to indicate the success of the operation, as well as a uniqueId of the account being managed. These were choices to facilitate test validation and to be more "visual" at the terminal.


## Build

### Requirements

 - Docker
 - `docker-compose` command-line tools
 - *Visual Studio Code* (optional, to run vscode tasks)

### How-to

#### 1. Build

**Prior to any execution or validation, the application image must be builded.**

If you are using vscode tasks, you do not need to perform any actions because building is a prerequisite for any other task. If you are using the terminal, you can build with the following command:

```
$ docker-compose build
```


#### 2. Tests

To run the tests, run the vscode `test` task or the following command from the terminal:

```
$ docker-compose run --rm php composer test
```


#### 3. Code coverage artifacts

PHPUnit is capable of generating configurable artifacts of unit and integration testing code coverage. To do this, run the vscode `test-coverage` task or the following command from the terminal:

```
$ docker-compose run --rm php composer test-coverage
```

Results will be generated inside the `/build` directory in the project root.
Within `/build`, the `html` directory will have a visual result, in html, of the percentage of test coverage and the state of the tests at each line of code.


#### 4. Syntax check

`phpcs` is a tool capable of validating code syntax according to some defined rules. I used the [PSR-12](https://www.php-fig.org/psr/psr-12/) standard, recently approved by the community of [PHP-FIG](https://www.php-fig.org/).

To validate the code syntax, run the vscode `check-syntax` task or the following command from the terminal:

```
$ docker-compose run --rm php composer check-syntax
```


#### 5. Run the application

To run the application and add input, use the following command at the terminal:

```
$ docker-compose run --rm php composer app /path/to/operations
```

There is an example with some operations in the `operations` file in the project root. To run the application with this example, use the following command at the terminal:

```
$ docker-compose run --rm php composer app operations
```
