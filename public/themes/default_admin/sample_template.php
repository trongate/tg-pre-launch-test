<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= BASE_URL ?>css/trongate.css">
    <link rel="stylesheet" href="<?= THEME_DIR ?>css/admin.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
    <h1>Headline Ahoy!</h1>
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Expedita ex <a href="#">Here is a link</a> reprehenderit non dolores delectus tempora molestias. Quisquam tempore, nostrum modi similique aliquam necessitatibus quis eos voluptates aliquid, rerum vero, ut?

    </p>
    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  </p>
    <h2>Sub Headlines Look Like This</h2>
    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  </p>
    <h3>H3 Headline look like this</h3>

    <table>
        <tr>
            <th>One</th>
            <th>Two</th>
            <th>Three</th>
            <th>Four</th>            
        </tr>
        <tr>
            <td>One</td>
            <td>Two</td>
            <td>Three</td>
            <td>Four</td>            
        </tr>
        <tr>
            <td>One</td>
            <td>Two</td>
            <td>Three</td>
            <td>Four</td>            
        </tr>
        <tr>
            <td>One</td>
            <td>Two</td>
            <td>Three</td>
            <td>Four</td>            
        </tr>
        <tr>
            <td>One</td>
            <td>Two</td>
            <td>Three</td>
            <td>Four</td>            
        </tr>
        <tr>
            <td>One</td>
            <td>Two</td>
            <td>Three</td>
            <td>Four</td>            
        </tr>

    </table>


    <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  Lorem ipsum dolor sit, amet consectetur adipisicing elit. Nihil saepe ipsa excepturi accusamus est officia. Dignissimos qui a id sint vitae asperiores doloribus, sapiente inventore repellat, laboriosam eligendi! Facilis, atque.  </p>
    <ul>
        <li>Here we go</li>
        <li>Another item</li>
        <li>Yet another</li>
    </ul>
    <h1>Some Headline</h1>
    <p>Fill out the form and hit 'Submit'.</p>
    <form action="http://localhost/css_project/submit" method="post">
        <label>Your Name</label>
        <input type="text" name="name" value="" placeholder="Enter your name here">
        <label>Favourite Movie</label>
        <select name="favourite_move">
<option value="" selected>Select movie...</option>
<option value="Star Wars">Star Wars</option>
<option value="First Blood">First Blood</option>
</select>        <label>First Name</label>
        <input type="text" name="name" value="">
        <label>Staying for lunch?</label>
        <input type="checkbox" name="whatever" value="1" checked> remember me
        <label>What is your gender?</label>

  <input type="radio" id="male" name="gender" value="male"> Male
  <input type="radio" id="female" name="gender" value="female"> Female
  <input type="radio" id="other" name="gender" value="other"> Leftist



        <label>Email</label>
        <input type="email" name="email" value="">
        <label>Password</label>
        <input type="password" name="password" value="">
        <label>Information</label>
        <textarea name="information" cols="30" rows="10" placeholder="Add some information here"></textarea>
        <div>
            <button type="submit" name="submit" value="Submit">Submit</button>
            <button class="alt" type="submit" name="submit" value="Cancel">Submit</button>  
            <button class="danger">Delete</button>  
        </div>
        <div>
            <a href="#"><button type="button" name="submit" value="Submit">Submit</button></a>
            <a href="#"><button class="alt" type="button" name="submit" value="Cancel">Submit</button></a>    
        </div>






        <div style="font-size: 0.8rem;">
            <button>Super Cool</button>
            <button class="alt">Another One</button>
        </div>

        <div>
            <button>Super Cool Small</button>
            <button class="alt">Another One Small</button>
        </div>

        <div style="font-size: 24px;">
            <button>Super Cool Large</button>
            <button class="alt">Another One Large</button>
        </div>

        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus odio omnis maiores nesciunt maxime, est ut, doloremque libero optio sit, saepe necessitatibus aut. Impedit earum hic repellendus officiis libero accusantium.  Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus odio omnis maiores nesciunt maxime, est ut, doloremque libero optio sit, saepe necessitatibus aut. Impedit earum hic repellendus officiis libero accusantium.  Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus odio omnis maiores nesciunt maxime, est ut, doloremque libero optio sit, saepe necessitatibus aut. Impedit earum hic repellendus officiis libero accusantium.  Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus odio omnis maiores nesciunt maxime, est ut, doloremque libero optio sit, saepe necessitatibus aut. Impedit earum hic repellendus officiis libero accusantium.  </p>
        <hr>
        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus odio omnis maiores nesciunt maxime, est ut, doloremque libero optio sit, saepe necessitatibus aut. Impedit earum hic repellendus officiis libero accusantium.  Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus odio omnis maiores nesciunt maxime, est ut, doloremque libero optio sit, saepe necessitatibus aut. Impedit earum hic repellendus officiis libero accusantium.  Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus odio omnis maiores nesciunt maxime, est ut, doloremque libero optio sit, saepe necessitatibus aut. Impedit earum hic repellendus officiis libero accusantium.  Lorem ipsum dolor sit amet consectetur adipisicing elit. Temporibus odio omnis maiores nesciunt maxime, est ut, doloremque libero optio sit, saepe necessitatibus aut. Impedit earum hic repellendus officiis libero accusantium.  </p>













        
    </form>
    <p style="height: 900px;">&nbsp;</p>
</div>
</body>
</html>