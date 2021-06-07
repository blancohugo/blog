# Blog

[![Build and test](https://github.com/blancohugo/blog/actions/workflows/php.yml/badge.svg)](https://github.com/blancohugo/blog/actions/workflows/php.yml) 
[![codecov](https://codecov.io/gh/blancohugo/blog/branch/master/graph/badge.svg?token=M2DPJJPVS0)](https://codecov.io/gh/blancohugo/blog) [![Blog staging](https://img.shields.io/website?down_color=red&down_message=down&label=staging&up_color=green&up_message=up&url=https%3A%2F%2Fblancohugo-blog-staging.herokuapp.com)](https://blancohugo-blog-staging.herokuapp.com/) [![Blog](https://img.shields.io/website?down_color=red&down_message=down&label=production&up_color=green&up_message=up&url=https%3A%2F%2Fblancohugo-blog.herokuapp.com)](https://blancohugo-blog.herokuapp.com/)

This is blog published in [blancohugo.com](http://blancohugo.com) - a blog engine written in PHP, using Laminas Mezzio and some PSRs.

This application is deployed to Heroku with test, staging and production environments.

# Content

This engine blog loads `Markdown` files to render pages and posts, inside the `/resources` directory.

The filenames are used directly as routes, but some of them have some additional features.

## Attributes

There are some attributes to use in the first line of the content files. Depending on the content type, some attributes are optional and some don't even exist.

All of them expect string values that will be parsed after loaded, so there's no need to use double or single quotes.

Write the attributes with no empty lines between them and _at least_ an empty line after the entire block.

Attributes example:

```md
@title This is my first post
@author Hugo Blanco
@date 2019-11-05
@summary Some quick example text to build on the card title and make up the bulk of the card's content.
@published true

## My document

This is my document!
```


## Pages

Directory: `/resources/md/posts`\
Pattern: `page-title.md`\
Path: `/page/<filename_without_extension>`

### Examples

| Filename      | Path           |
|---------------|----------------|
| `about.md`    | /page/about    |
| `projects.md` | /page/projects |

### Attributes

_* mandatory attributes_

- `@title`* - Page title
- `@author` - Page author

## Posts

Directory: `/resources/md/posts`\
Pattern: `YYYY-MM-DD-post-title.md`\
Path: `/post/<filename_without_extension>`

### Examples

| Filename                       | Path                            |
|--------------------------------|---------------------------------|
| `2021-01-02-using-markdown.md` | /post/2021-01-02-using-markdown |
| `2019-10-09-shell-script.md`   | /post/2019-10-09-shell-script   |

The posts use date in the file name to facilitate the ordering, since the date attribute is optional.

### Attributes

_* mandatory attributes_

- `@title`* - Post title
- `@author` - Post author
- `@date` - Post creation date (YYYY-MM-DD)
- `@summary`* - Post summary (short description)
- `@published` - Published status (only "true" and "false" are valid)

# Local testing

This project can be tested locally using `docker compose` config.

You don't need any PHP version installed and `docker` is the only requirement.

To start the application:

```bash
$ docker compose up
```

To stop the application, after `ctrl + c`:

```bash
$ docker compose down
```

The application should be accessible in `http://localhost`, using the `80` port.

# Contributing

Fork this project to start contributing.

Pull requests will trigger automatic builds and, after they succeed, an automatic Heroku container will be deployed to a test environment. All builds and deploys will be linked to the PR.

To be approved, your code should follow the rules:

- All code in `/src` should be covered by tests
- The builds that validate code syntax, check unit tests and code coverage should pass
- The application should be accessible in the test environment with no errors