
<div id="rightPanel">

<!-- WARNING
  <p class="warning">{$warning}</p>
  <p>&nbsp;</p>
 -->

  <h2>Login</h2>
  
  <form method="post">
  <input name="firstname" type="text" value="First Name" onfocus="if(this.value=='First Name')this.value=''" onblur="if(this.value=='')this.value='First Name'"/>
  <div class="blank"></div>
  <input name="lastname" type="text" value="Last Name" onfocus="if(this.value=='Last Name')this.value=''" onblur="if(this.value=='')this.value='Last Name'"/>
  <input name="password" type="password" value="Password" onfocus="if(this.value=='Password')this.value=''" onblur="if(this.value=='')this.value='Password'"/>  
  <input type="submit" name="login" class="login" value="Login"/>
  </form>
  
  <p>&nbsp;</p>
  
  <h2>Visitor Login</h2>
  <p>Visiting from another world? Enter the URL of your home world to login</p>
  
  <form method="post">
  <input name="home_url" id="home_url" type="text" value="http://www.myhomeworld.com/" onfocus="if(this.value=='http://www.myhomeworld.com/')this.value=''" onblur="if(this.value=='')this.value='http://www.myhomeworld.com/'"/>
  <input type="button" name="login" class="login" value="Login" onclick="fetchXrd();return false;"/>
  </form>
  <div id="visitor_login_status"></div>
</div>
 
<div id="quots"><p></p></div>


<div id="fb-root"></div>
<script src="/static/javascript/prelude.js"></script>
<script src="/static/javascript/content.js"></script>
<script src="/static/javascript/frames.js"></script>
<script src="/static/javascript/flash.js"></script>
<script src="/static/javascript/qs.js"></script>
<script src="/static/javascript/xd.js"></script>
<script>
  /**
   * Open a popup that starts the XRD fetch process from the entered URL
   */
  function fetchXrd() {
    var remote_url = document.getElementById('home_url').value;
    
    if (!remote_url || remote_url == 'http://www.myhomeworld.com/') {
      document.getElementById('visitor_login_status').innerHTML = 
        '<p class="warning">Please enter a valid virtual world URL</p>';
    } else {
      document.getElementById('visitor_login_status').innerHTML = '';
        
      var g = FB.guid();
      var callback_url = FB.Frames.xdHandler(seedCapResponse, g, "opener", true);
      var url = '/fetch_xrd?' + FB.QS.encode({home_url: encodeURIComponent(remote_url), wrap_callback: encodeURIComponent(callback_url)});
      
      FB.Frames.popup(url, 450, 415, g);
    }
  };

  /**
   * Receive a delegated agent seed capability with OAuth WRAP and POST it back
   * to this page.
   */
  function seedCapResponse(response) {
    if (response.wrap_access_token) {
      var seedcap = response.wrap_access_token;
      formPost(document.URL, { remote_seed_cap: seedcap });
    } else {
      if (response.error === 'xrd_failed') {
        document.getElementById('visitor_login_status').innerHTML = 
          '<p class="warning">Could not find a virtual world at that address</p>';
      } else if (response.error === 'user_denied') {
        document.getElementById('visitor_login_status').innerHTML = 
          '<p class="warning">Login cancelled</p>';
      } else {
        document.getElementById('visitor_login_status').innerHTML = 
          '<p class="warning">An unknown error occurred</p>';
      }
    }
  };

  /**
   * POSTs an associative array of data to a URL.
   */
  function formPost(url, inputs) {
    var myForm = document.createElement("form");
    myForm.method = "post";
    myForm.action = url;
    
    for (var key in inputs) {
      var myInput = document.createElement("input");
      myInput.setAttribute("name", key);
      myInput.setAttribute("value", inputs[key]);
      
      myForm.appendChild(myInput);
    }
    
    document.body.appendChild(myForm);
    myForm.submit();
    document.body.removeChild(myForm);
  };
</script>
