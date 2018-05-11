
## Customising the Form

#### Settings

1) Go to the _Settings_ page (submenu in **Forms** top level menu).

2) Click the **Disable JavaScript** and **Disable CSS** checkboxes.

#### Add Files to Theme

```
├── public
│   ├── sass
│   │   └── wp-swift-form-builder-public.scss
│   ├── css
│   │   └── wp-swift-form-builder-public.css
│   ├── fonts
│   │   ├── icomoon.eot
│   │   ├── icomoon.svg
│   │   ├── icomoon.ttf
│   │   └── icomoon.woff
│   ├── js
│   │   ├── wp-swift-form-builder-public.js
```

3) Copy the sass file from `public/sass/wp-swift-form-builder-public.scss` into your own theme and add it using an @import statement in your main sass file.

4) Copy the JavaScript file from `public/js/wp-swift-form-builder-public.js` into your own theme and import using [**webpack**](https://webpack.js.org/) (or whatever way you want to include it).

5) Copy the fonts directory `public/fonts/` into the same folder as your that contain your CSS folder.

```
assets
└── scss
    ├── _form.scss
├── css
│   └── app.css
├── fonts
│   ├── icomoon.eot
│   ├── icomoon.svg
│   ├── icomoon.ttf
│   └── icomoon.woff
└── js
    └── app.js
```

#### Customise
You can now customise the how you wish.

##Sass Snippets

Some useful sass snippets to add to your custom style.

##### Keep labels and inputs in the same row.

```sass
div.form-group {
	@extend .grid-x;
	.form-label {
		@extend .cell;
		@extend .small-12;
		@extend .medium-3;
	}
	.form-input {
		@extend .cell;
		@extend .small-12;
		@extend .medium-10;
	}	
}
div.form-group.button-group, div.form-group.section-content, div.form-group#captcha-wrapper {
	.form-input {
		@extend .small-12;
		@extend .medium-offset-3;
	}		
}
```

##### Position Google Recaptcha next to Submit button

```sass
/**
 * Position Google Recaptcha next to Submit button
 */
#form-submission-wrapper {
    padding-top: 1rem;
}  
#submit-request-form {
    @extend .expanded;
}
div.form-builder .form-group.button-group {
    .form-input{
        width: 100%;
    }    
}
@media screen and #{breakpoint(large)} {
    #captcha-wrapper {
        padding-top: 0.5rem;
        padding-right: 1rem;
        float: left;
        padding-right: 0.5rem;
    }
    .form-group.mail-receipt {
        padding-bottom: 0;
    } 
    div.form-builder .form-group.button-group {
        padding-left: 0;   
    }  
}
```
