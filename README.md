# ACF Post Ratings Field

Adds a new ACF field for ratings

### Structure

* `/assets`:  folder for all asset files.
* `/assets/css`:  folder for .css files.
* `/assets/images`: folder for image files
* `/assets/js`: folder for .js files
* `/fields`:  folder for all field class files.
* `/fields/postrating-v5.php`: Field class compatible with ACF version 5
* `/fields/postrating-v4.php`: Field class compatible with ACF version 4
* `/lang`: folder for .pot, .po and .mo files
* `acf-postrating.php`: Main plugin file that includes the correct field file based on the ACF version
* `readme.txt`: WordPress readme file to be used by the WordPress repository

### step 1.

This template uses `PLACEHOLDERS` such as `postrating` throughout the file names and code. Use the following list of placeholders to do a 'find and replace':

* `postrating`: Single word, no spaces. Underscores allowed. eg. donate_button
* `Post Ratings`: Multiple words, can include spaces, visible when selecting a field type. eg. Donate Button
* `https://bitbucket.org/idea-rebel/wp-post-rating-plugin`: Url to the Bitbucket repository
* `acf, rating, stars`: Comma separated list of relevant tags
* `SHORT_DESCRIPTION`: Brief description of the field type, no longer than 2 lines
* `EXTENDED_DESCRIPTION`: Extended description of the field type
* `AUTHOR_NAME`: Name of field type author
* `AUTHOR_URL`: URL to author's website

### step 2.

Edit the `postrating-v5.php`

### step 3.

Edit this `README.md` file with the appropriate information and delete all content above and including the following line.

-----------------------

# ACF Post Ratings Field

SHORT_DESCRIPTION

-----------------------

### Description

EXTENDED_DESCRIPTION

### Compatibility

This ACF field type is compatible with:
* ACF 5
* ACF 4

### Installation

1. Copy the `acf-postrating` folder into your `wp-content/plugins` folder
2. Activate the Post Ratings plugin via the plugins admin page
3. Create a new field via ACF and select the Post Ratings type
4. Please refer to the description for more info regarding the field type settings

### Changelog
Please see `readme.txt` for changelog
