PHP GMail Parser
================

This class can be helpful to parse Google Mail addresses lists and return their basic version. As known Gmail supports many versions of the same email address local-part, for example:

```
user.name@gmail.com
UserName+addressing@gmail.com
usermail@googlemail.com
```

are all referencing to the same account, i.e. <nohref><code>username@gmail.com</code></nohref>.

Scope of this class is to return the basic version of a GMail address, but it can also perform a simple check to return a boolean response.

The public methods are <code>isGmail()</code> and <code>parseMail()</code>, both supports strings and arrays.

<h3>isGmail()</h3>

Will return boolean <code>true/false</code> for strings; an array will be returned when submitting a list of email addresses, the response will contain only valid GMail addresses, in their basic version.

<h3>parseMail()</h3>

This method is less restrictive than <code>isGmail()</code> because it will not exclude other domain names from the response.

Some examples:

``` php
# tests
$list = array(
	'user.name@anemail.com',
	'username+acme@gmail.com',
	'email' => 'another@gmail.com',
	array(
		'test@ymail.com',
		'will+fail@gmail.com',
		'this032@googlemail.com',
		'"valid@test"@email.com',
		'Awesome@yahoo.com'
	),
	'someone+doh@gmail.com',
	'AnotherUser+focus@gmail.com',
	'simple@gmail.com'
);

$gm = new GMailParser;

var_dump($gm->isGmail('user.name@amail.com'));
var_dump($gm->isGMail('user.name@gmail.com'));
/*
bool(false)
bool(true)
*/


var_dump($gm->parseMail('user.name@amail.com'));
var_dump($gm->parseMail('user.name@gmail.com'));
/*
string(19) "user.name@amail.com"
string(18) "username@gmail.com"
*/

print_r($gm->isGmail($list));
/*
Array
(
    [0] => username@gmail.com
    [1] => another@gmail.com
    [2] => this032@gmail.com
    [3] => someone@gmail.com
    [4] => anotheruser@gmail.com
    [5] => simple@gmail.com
)
*/

print_r($gm->parseMail($list));
/*
Array
(
    [0] => user.name@anemail.com
    [1] => username@gmail.com
    [2] => another@gmail.com
    [3] => test@ymail.com
    [4] => this032@gmail.com
    [5] => "valid@test"@email.com
    [6] => awesome@yahoo.com
    [7] => someone@gmail.com
    [8] => anotheruser@gmail.com
    [9] => simple@gmail.com
)
*/
```

I use this code to verify usernames or to extract an email address from an array. Hope is useful for you.

<h4>Note</h4>
A copy of this code can be found in daniweb.com at this link:

* https://www.daniweb.com/web-development/php/code/488524/parse-google-gmail-aliases

That's me :)
