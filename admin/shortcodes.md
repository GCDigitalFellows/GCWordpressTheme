Shortcodes
=========

These are the shortcodes included with this theme that will generate stylized content based on the built-in theme styles.

  - [list_people](#list_people)
  - [list_projects](#list_projects)
  - [blockquote](#blockquote)
  - [alert](#alert)
  - [button](#button)
  - [gallery](#gallery)

----

<a name="list_people"></a>list_people
---

###Description

Displays a responsive gallery of 'people' custom post types. This uses essentially the same display format as people archive pages, but allows you to include them on other pages.

###Arguments
  - people_categories: [category slugs]. Specify one or more people categories to include.
  - list_people accepts the standard WordPress arguments for the [WP_Query function](http://codex.wordpress.org/Class_Reference/WP_Query).

####Example:
```
[list_people people_categories='faculty' posts_per_page=4 orderby='name']
```

-----

<a name="list_projects"></a>list_projects
---

###Description

Displays a responsive gallery of 'project' custom post types. This uses essentially the same display format as project archive pages, but allows you to include them on other pages.

###Arguments
  - project_categories: [category slugs]. Specify one or more project categories to include.
  - project_tags: [tag slugs]. Specify one or more tags to include.
  - list_projects accepts the standard WordPress arguments for the [WP_Query function](http://codex.wordpress.org/Class_Reference/WP_Query).

####Example:
```
[list_projects project_categories='faculty' project_tags='featured' posts_per_page=4 orderby='name']
```

----

<a name="blockquote"></a>blockquote
---

###Description

Displays a Bootstrap formatted blockquote with an optional author attribution and the ability to "float" the blockquote on the left or right side of the page.

###Arguments
  - float (optional): ``left`` or ``right``. Floats the blockquote on the left or right side of the page, allowing other elements to wrap around it.
  - cite (optional): [text citation]. Include a citation/attribution for the quote, displayed in small text below the quote.

####Example:
```
[blockquote float='left' cite='Mark Twain']It is better to keep your mouth closed and let people think you are a fool than to open it and remove all doubt.[/blockquote]
```

----

<a name="alert"></a>alert
---

###Description

Displays a Bootstrap formatted alert box in one of several colors. Links included in the warning boxes will be styled according to the "type" argument. 

###Arguments
  - type: ``info`` (default), ``success``, ``warning`` or ``error``. Controls the color of the alert box. Styling is generally based on the [Bootstrap alert box styles](http://getbootstrap.com/components/#alerts)
  - close: ``true`` or ``false`` (default). Display an "x" button to close the alert box.

####Example:
```
[alert type='warning' close='true']This is a dismissable warning box! <a href="#">Click this link for more info</a>[/alert]
```

----

<a name="button"></a>button
---

###Description

Displays a Bootstrap formatted "button" link.

###Arguments
  - type: ``default``, ``primary``, ``info``, ``success``, ``danger``, ``warning``, ``inverse``, ``orange``, ``yellow``, ``green-light``, ``green``, ``green-dark``, ``blue-light``,  ``blue``, ``blue-dark``, ``pink``, ``gray``. The type specifies what color scheme to use. In addition to the default Bootstrap options, the theme provides several additional colors based on the theme's color palette.
  - size: ``xs`` (extra small), ``sm`` (small), ``default`` or ``md`` (medium), ``lg`` (large)
  - block: ``true`` or ``false`` (default). Display the button at the full width of its parent. See description in the [Bootstrap Documentation](http://getbootstrap.com/css/#buttons-sizes)
  - active: ``true`` or ``false`` (default). Make the button appear as though it is in a depressed state. See description in the [Bootstrap Documentation](http://getbootstrap.com/css/#buttons-active)
  - url: [URL including "http://"] link to open when the button is clicked]
  - target: ``_self`` (default), ``_blank``, etc. Specify whether the link should open in the same window (``_self``) or a new window (``_blank``). Any valid html anchor targets will work.
  - text: [button text]. The text that is displayed on the button.
  

####Example:
```
[button type='primary' url='http://hyperlink.com' text='Click Me!']
```

----

<a name="gallery"></a>gallery
---

###Description

Displays a Bootstrap styled gallery. Takes the same arguments as the built-in wordpress gallery.