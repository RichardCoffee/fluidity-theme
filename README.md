# fluidity-theme

A WordPress theme I use as a parent theme.  I am trying to add docblocks to all
files, classes, and functions, but since I use vi as my editor, the docblocks
don't really do me any good, and I have no way of testing their usefulness.
Could someone look at this for me?

## Theme files - root directory

This section covers all theme root directory files.  If you are at all familiar
with how a WordPress theme works, then what the majority of these files are,
and how they work, should already be known to you.

### Pages

These are the files used for specific or general pages.

#### 404.php

Handles all 404 occurrences.

#### index.php

Default template for displaying a post or page.

#### page.php

Default template for displaying a page.

#### printer.php

Not currently used.  This was originally written to utilize [the TCPDF library](https://tcpdf.org/),
but that project is no longer being developed or supported.  It's replacement,
[tc-lib-pdf](https://github.com/tecnickcom/tc-lib-pdf), is not yet ready for primetime.
In the end I decided not to use the library at all, but have kept this file
around in case I change my mind.

#### stock.php

Main template I use for pages.  In fact, index.php and page.php are just dups of
this file.

### WordPress specialty files

These are those files required by WordPress to handle specific tasks.  Again, if
you know WordPress, you'll know about these.

#### comments.php

Handles the comment area on a post page.

#### footer.php

Displays the footer area.

#### functions.php

This makes the theme work. :)

#### header.php

Displays the header area.

#### searchform.php

Displays the searchform.

#### sidebar.php

Displays the sidebar widget areas.

#### style.css

Main css file.

### Miscellaneous

#### favicon.ico

The image that shows up in the tab of the browser.


## Classes

The theme uses a variety of classes, all residing in the classes/ directory,
and organized mainly via sub-directories.  Wordpress frowns on this type of
organization, which is okay.  I'll do it how I want, and they can do it the way
they want.

All classes are loaded via the includes/loader.php file.  Take a look at that
file to see which classes can be replaced/extended by a child theme.  All class
names use the same format, i.e.: the class TCC_Form_Admin is located in the file
classes/Form/Admin.php, which means if you know the class name, then you can
easily find the file containing the class.


### Forms

The forms classes deal with admin page forms, customizer controls, comment forms,
and the login form.


#### Admin Forms

The class TCC_Form_Admin is an abstract class used a basis for all admin forms,
and uses the WordPress Settings API.  Currently, the only class the theme uses
this for is the TCC_Options_Fluidity class, which is a tabbed form.  I have
used it in some plugins, although only the [Privacy My Way plugin](https://github.com/RichardCoffee/privacy-my-way)
is available to be viewed by the general public.  I still consider this class
to be a work in progress in many ways.

#### Customizer Controls

The class TCC_Form_Control_Customizer handles adding each individual control to
the WordPress customizer.  The TCC_Form_Control_Control class extends the WP_Customize_Control
class, and all other theme customizer controls are children of this class.  I
think I should have named the classes differently, i.e.: TCC_Form_Customize_Customizer
and TCC_Form_Customize_Control, but haven't gotten around to fixing that yet.

##### Checkbox control

I wrote the TCC_Form_Control_Checkbox class because the WordPress control wouldn't
allow a title for the checkbox.

##### Content control

The TCC_Form_Control_Content simply displays a title and some text on the screen, no inputs at all.

##### HTMLRadio control

I wanted to be able to display some font awesome icons in the radio choices,
which required using HTML, something WordPress would not allow.

##### MultipleCB control

Will display a title, followed by multiple checkboxes.

##### MultipleSel control

Will display a select field that allows multiple choices.


#### Comment forms

I wrote the TCC_Form_Comment class to give myself somewhat better control over
how the comment form is displayed.  It makes heavy use of my [TCC_Trait_Attributes
trait](https://github.com/RichardCoffee/fluidity-theme/blob/master/classes/Trait/Attributes.php).


#### Login form

The theme's login form, suitable for use in a navbar or a widget.  Should also
be able to be used in a modal, although I still haven't written the modal code.


### Metaboxes

The theme includes an abstract class, TCC_Metabox_Metabox, which I use for admin
metaboxes, although it gets used mainly in plugins.

#### PostDate

TCC_MetaBox_PostDate works with the post edit page, controlling how the
date/author is displayed with each individual post.

#### Sidebar

TCC_MetaBox_Sidebar is also for the post edit page.  It allows the author to
control whether the sidebar is displayed with the post.


### Modals

Modals are a Bootstrap feature.  The theme has an abstract class TCC_Modal_Bootstrap,
used as a basis for all modal classes.  I have used this class in other projects, but...

#### Login modal

Hmmm, TCC_Modal_Login - never finished.  PR anyone?


### NavWalker

The theme currently uses TCC_NavWalker_Bootstrap, loosely based on [this one on github](https://github.com/wp-bootstrap/wp-bootstrap-navwalker).

#### Taxonomy

This is used to dynamically add a taxonomy's terms to a menu.


### Options

In addition to the customizer options, the theme provides a tabbed option screen,
using TCC_Form_Admin as the parent class.  I have shifted many options to the
customizer where possible.  Take a look at the files in the classes/Options directory
for an idea of what's going on there.


## CSS

### Sass

### Color Schemes


## includes


## Javascript


## Languages


## Page Templates


## Template Parts

## Vendors

### External Libraries

#### Bootstrap

Currently uses Bootstrap 3.3.7, with plans to upgrade to version 4 as time permits.

#### Font Awesome

Currently uses Font Awesome 4.7.0, with no plans to upgrade to version 5 at this time.

### External Files

#### custom_menu_items.php

Comes from [this gist](https://gist.github.com/daggerhart/c17bdc51662be5a588c9).  Used by TCC_NavWalker_Taxonomy to add
taxonomy terms to a menu.


## Pull Requests

Any pull request made within the spirit of the theme may be accepted.
