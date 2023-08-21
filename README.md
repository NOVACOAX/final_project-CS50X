# MAgPIE Gallery
#### Video Demo:  [YouTube Demo](https://www.youtube.com/watch?v=7r6SLpSs1bo&t=19s)
#### Description: Hello Wolrd from kenya.:kenya: For my CS50 final project,  I made a gallery to showcase my photos how I wanted.  There are 3 categories of `users` with differrent roles/permissions. There is a secured log in system in place.

#### `admin` roles:
- post photos
- veiw and reply to feedback
- make a user an editor
- delete any user
- delete any photo
- edit any photo
- deactivate a user
- edit their account
- like photos
#### `editor` roles:
- post photos
- delete their account
- delete their photos
- deactivate their account
- edit their photos
- edit their account
- like photos
#### `user` roles:
- view photos
- delete their account
- deactivate their account
- edit their account
- like photos

### Languages used:
- PHP
- SQL
- JavaScript
- jQuery
- jQuery -> Ajax
- HTML/CSS

### Resources:
- [Lightbox](http://lokeshdhakar.com/projects/lightbox2/)
- [Boxicons](https://boxicons.com/)
- [Bootstrap](https://getbootstrap.com/)
- [Material Design for Bootstrap v5 & v4](https://mdbootstrap.com/)
- [Fontawesome](https://fontawesome.com/v4/)
- [jQuery](https://jquery.com)
- [jQuery DataTables](https://datatables.net/)
- [PHPMailer](https://github.com/PHPMailer/)


## Files
### - index.php
Displays a css grid with the photos.
### - dashboard.php
only admins and editors have access to this file.
   - Display visit count.
   - Display photo count.
   - Display user count.
   - View, delete and reply to feedback.
   - View, delete and edit photos.
   - view users.
   - View accept or delete Editor requests.
   - View 10 random user accounts.
### - user-profile.php
   - View own|selected profile.
   - Edit/disable/delete own profile.
   - View own|selected profile posts/followers/following.
   - Follow/unfollow selected profile.
### - uploadPics.php
Select, edit, and upload multiple images.
### - ajaxCalls.php
Handles the ajax calls declared in `scripts.js`.
### - 404.php
Fallback page. 
### - signup.php
Display a form with name,email,password,confirm-password inputs.
### - login.php
Display a form with name & password input.
### - logout.php
Delete session & redirect to index.php.
### - sendmail.php
Receives data via post method and send it to the specified email.
### - functions.php
Defines various functions used in the website.
### - dbconnect.php
Used to declare the connection to database.
### - footer.php
Close the body tag & link the needed scripts.
### - footerinfo.php
Declare the footer div.
### - header.php
Open the HTML & body tags. Declare the metatags & link the CSS
### - nav-bar.php
Declare the top navigation bar used in most pages. Display any message if there is any.
### - sidebar.php
Declare the sidebar used in the dashboard,uploadPic,user-profile pages.
### - scripts.js
Declare JavaScript, jQuery & jQuery->Ajax functions. It handles basic web funcionality and content manipulation after Ajax calls.
### - styles.css
Used to style the website.

## Notes:
:warning: **For copywrite reasons, none of the photos used. in the video demo will be provided.**

 Default Admin-> name: `admin` password: `123` email: `admin@admin.com` type/role: `323`.
 
 Default Editor-> name: `editor` password: `123` email: `editor@editor.com` type/role: `222`.
 
 Default User-> name: `user` password: `123` email: `user@user.com` type/role: `111`.
 
 #### Running the website:
 
- :arrow_right: To run this website on your computer you'll need `XAMPP` installed.
- copy all the files to `xampp/htdocs/gallery`.
- start the software and run `MySQL Database` and `apache web server`.
-  exoprt the sql file into the database adn name it `FinalProject`.
- in your browser direct to this page `localhost/gallery/`
         
<details><summary>changing gallery columns:</summary>
<p>

#### Example -> change to 6 cols.
##### Style.css
```css
   .Gallery.container{
    grid-template-columns: repeat(6,1fr);
   }
   /** add one of this for each col **/
   .w-6{
    grid-column: span 6;
   }
```
##### dashboard.php
```html
   <!--- form#editPicForm > div.row > div.col-6:last-child > append this code --->
   <div class="form-check">
       <input class="form-check-input" type="radio" name="width" id="w6" value="6">
       <label class="form-check-label" for="w6">5</label>
   </div>
```
##### uploadPics.php
```html
   <!--- div.editImage > div.row > div.col-6:last-child > append this code --->
   <div class="form-check">
       <input class="form-check-input" type="radio" name="width" id="w6" value="6">
       <label class="form-check-label" for="w6">5</label>
   </div>
```
</p>
</details>

#### Conclusion:
This was an exciting project to work on.The project is not ready to host yet but i will continue to work on it and soon showcase my Photography work to the world. I have learned a lot in this course to help me  in programming and would wish to continue with other CS50 courses in future.


**My name Hugh Herschell and this was CS50**
