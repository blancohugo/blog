@title Exciting new PHP 8 features
@author Hugo Blanco
@date 2021-06-13
@summary Check some of the exciting new features in the PHP 8 version and start using it!
@published true

## PHP 8 was released!

A major version of the language was released and it contains many new features and optimizations. You can check the full list [here](https://www.php.net/releases/8.0/en.php), but I'll list the most exciting ones in this article. 

### Named arguments

Who has never created temporary variables to improve the readability of a method or had to pass all the optional parameters to update the last one?

```php
htmlspecialchars($string, ENT_COMPAT | ENT_HTML401, 'UTF-8', $doubleEncode = false);
```

With named arguments, this will no longer be necessary:

```php
htmlspecialchars($string, double_encode: false);
```

We just need to specify required parameters, skipping optional ones. Arguments are order-independent and self-documented, so we could pass the `double_encode` value, which is the last one, without providing the others.

### Attributes

Some frameworks and ORMs could require PHPDoc annotations in classes to avoid demanding additional configuration files. The problem with this approach is that it "dirties" the class docs, also making it difficult to parse the values:

```php
class PostsController
{
    /**
     * @Route("/api/posts/{id}", methods={"GET"})
     */
    public function get($id) { /* ... */ }
}
```

With attributes, we have a native syntax, to be used outside of the docblock: 

```php
class PostsController
{
    #[Route("/api/posts/{id}", methods: ["GET"])]
    public function get($id) { /* ... */ }
}
```

To get those attributes, we use a pretty similar `Reflection` method:

```php
$reflection = new ReflectionClass(PostsController::class);

// Using annotations
$reflection->getDocComment(); // the string should be parsed to extract attributes
// Using attributes
$reflection->getAttributes();
```

More details in the [official doc](https://www.php.net/manual/en/language.attributes.php).

### Union types

This is my favorite feature of this version. I've been using this syntax in Typescript for some time now, so it seems like a natural way to go.
Previously, developers needed to use the documentation to indicate the type and do some internal validations to ensure that invalid arguments were not being provided:

```php
class Number {
    /** @var int|float */
    private $number;

    /**
    * @param float|int $number
    */
    public function __construct($number) {
        $this->number = $number;
    }
}

new Number('NaN'); // Ok
```

With union types, this is much simpler:

```php
class Number {
    public function __construct(
        private int|float $number
    ) {}
}

new Number('NaN'); // TypeError
```

So instead of PHPDoc annotations for a combination of types, you can use native union type declarations that are validated at runtime.

### Nullsafe operators

Getting nested object properties can be very painful sometimes:

```php
$country =  null;

if ($session !== null) {
    $user = $session->user;

    if ($user !== null) {
        $address = $user->getAddress();

        if ($address !== null) {
            $country = $address->country;
        }
    }
}
```

But, with the new operator, we have:

```php
$country = $session?->user?->getAddress()?->country;
```

Instead of `null` check conditions, you can now use a chain of calls with the new nullsafe operator. When the evaluation of one element in the chain fails, the execution of the entire chain aborts and the entire chain evaluates to `null`.

### Match expression

The language `switch` is really permissive, but that could make the application fail silently

```php
switch (8.0) {
  case '8.0':
    $result = "Oh no!";
    break;
  case 8.0:
    $result = "This is what I expected";
    break;
}
echo $result;
//> Oh no!
```

And with `match`, we're much safer:

```php
echo match (8.0) {
    '8.0' => "Oh no!",
    8.0 => "This is what I expected",
};
//> This is what I expected
```

The new `match` is similar to `switch` and has the following features:
- Match is an expression, meaning its result can be stored in a variable or returned.
- Match branches only support single-line expressions and do not need a `break;` statement.
- Match does strict comparisons.