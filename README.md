# Leggio

Leggio is a file-based blogging platform inspired by Jekyll.  It can either be used directly on a web server, or it can generate flat files.  This is still a work in progress.

## Why

I love the idea of writing content with markdown. I want to control and version my work, and not be at the mercy of a poorly designed application or closed platform. I also want to be able to modify behaviour of the generated output.  With most file-based generators, there is no website.  There is the process of reading files and dumping them somewhere else.  Leggio is different.

Leggio runs as a web application, parsing HTTP requests and returning HTTP responses.  It can also dump the entire application as static files, mimicking projects like Jekyll or Second Crack without the limitations.

## Under the Hood

Leggio runs on top of Silex, and also a few other PHP components grabbed from Composer.  It has a very small footprint, and doesn't try to re-invent the wheel. It should be easy for any seasoned PHP developer to customize. 
