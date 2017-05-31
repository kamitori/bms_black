window.fbAsyncInit = function() {
  FB.init({
    appId      : '1055465601202070',
    xfbml      : true,
    version    : 'v2.4'
  });
  
  FB.Event.subscribe('edge.create', function(href, widget) {
    console.log('click like');
    ButtonLikeCallBack();
  });
};
// facebook fanpage id = 304137593043458
function _temp(response){  
  FB.api('/me',{fields: 'last_name,first_name,email,id'}, function(res) {  
  var use_id =  res.id;  
     var link = '/'+use_id+'/likes';
     console.log(use_id);
     console.log(link);
     console.log(res);
      FB.api(
          link,
          function (responses) {
            console.log(responses)
            if (responses && !responses.error) {
              /* handle the result */
            }
          }
      );
  });
  /*
  FB.api('/me/likes/','GET',{}, function(response) {
      console.log(response.data);
  });
  */
 
  
}
(function(d, s, id){
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = '/themes/pizzahut/js/facebook-sdk.js';
  fjs.parentNode.insertBefore(js, fjs);
  }(document, 'script', 'facebook-jssdk')
);
function checkLoginStateLike() {
    FB.getLoginStatus(function(response) {
      check_like(response);
    });
}
function check_like(response){
  if (response.status === 'connected') {
      // Logged into your app and Facebook.
      _temp(response);
    } else {
      FB.login(function(response) {
        checkLoginStateLike();
      }, {scope: 'public_profile,email'});
    }
}

function statusChangeCallback(response) {
    console.log('statusChangeCallback');
    console.log(response);
    // The response object is returned with a status field that lets the
    // app know the current login status of the person.
    // Full docs on the response object can be found in the documentation
    // for FB.getLoginStatus().
    if (response.status === 'connected') {
      // Logged into your app and Facebook.
      testAPI();
    } else {
      console.log('sorry not to see you');
      FB.login(function(response) {
        checkLoginState();
      }, {scope: 'public_profile,email'});
    }
}
function run_callback(){
  FB.api('/me',{fields: 'last_name,first_name,email,id'}, function(response) {    
    var email = response.email;
    var fb_id = response.id;
    var first_name = response.first_name;
    var last_name = response.last_name;    
    $.ajax({
      url : '/users/check-likeuser',
      type: 'POST',
      data:{
        email : email,
        fb_id : fb_id ,
        first_name:first_name,
        last_name:last_name
      },
      success:function(data){
        if(data.error==0){
          showAlert_h(data.title,data.message, 'blue');
        }
      },error:function(data){
        console.log(data)
      }
    })
  });
}
function LikeCallBack(response) {
    console.log('statusChangeCallback');
    console.log(response);
    if (response.status === 'connected') {
      run_callback();
    } else {
      FB.login(function(response) {
        checkLoginState();
      }, {scope: 'public_profile,email'});
    }
}
function testAPI() {
    console.log('Welcome!  Fetching your information.... ');
    FB.api('/me',{fields: 'last_name,first_name,email,id'}, function(response) {
          console.log(response);
        var email = response.email;
        var fb_id = response.id;
        var first_name = response.first_name;
        var last_name = response.last_name;
        $.ajax({
      url : '/users/check-user',
      type: 'POST',
      data:{
        email : email,
        fb_id : fb_id 
      },
      success:function(data){
        if(data==1){
          window.location = '/';
        }else{
          show_create_account(email,first_name,last_name,fb_id);
        }
      }
    })
    });
}
function ButtonLikeCallBack(){
   FB.getLoginStatus(function(response) {
    console.log('=====');
    console.log(response);
      LikeCallBack(response);
    });
}
function checkLoginState() {
    FB.getLoginStatus(function(response) {
      statusChangeCallback(response);
    });
}
function show_create_account(email,first_name,last_name,fb_id){
  $("#modal_fb").modal("show");
    $("#form_create_account #email").val(email);
    $("#form_create_account #re_email").val(email);
    $("#form_create_account #first_name").val(first_name);
     $("#form_create_account #last_name").val(last_name);
    $("#form_create_account #facebook_id").val(fb_id);
}

$("#modal_fb").modal({
  backdrop : false,
  show : false
})