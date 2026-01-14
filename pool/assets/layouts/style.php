<!DOCTYPE html>
<html lang="en">
<head>
    <title>Wheeleder</title>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no, shrink-to-fit=no"
        name="viewport" />
    <link rel="shortcut icon" href="/ui/assets/images/f-icon.png" />

    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js"crossorigin="anonymous"></script>

    <!-- select2-bootstrap4-theme -->

    <link rel="stylesheet" href="/ui/assets/css/custom-style.css" type="text/css" />
    <link rel="stylesheet" href="/ui/assets/sass/main.css" type="text/css" />
    <link rel="stylesheet" href="/ui/assets/sass/otherstyle.css" type="text/css" />

    <style>
    .user-log-box {
        display: none;
    }

    nav .user-pic:hover>.user-log-box {
        display: block !important;
    }

    .successmsg {
        text-align: center;
        position: fixed;
        top: 80px;
        z-index: 998;
        right: 10px;
    }

    @media all and (min-width: 992px) {
    .navbar .nav-item .dropdown-menu{ display: none; }
    .navbar .nav-item:hover .nav-link{   }
    .navbar .nav-item:hover .dropdown-menu{ display: block; }
    .navbar .nav-item .dropdown-menu{ margin-top:0; }
}	
/* ============ desktop view .end// ============ */
.stars-outer {
  display: inline-block;
  position: relative;
  font-family: FontAwesome;
}

.stars-outer::before {
  content: "\f006 \f006 \f006 \f006 \f006";
}

.stars-inner {
  position: absolute;
  top: 0;
  left: 0;
  white-space: nowrap;
  overflow: hidden;
  width: 0;
}

.stars-inner::before {
  content: "\f005 \f005 \f005 \f005 \f005";
  color: #f8ce0b;
}

.attribution {
  font-size: 12px;
  color: #444;
  text-decoration: none;
  text-align: center;
  position: fixed;
  right: 10px;
  bottom: 10px;
  z-index: -1;
}
.attribution:hover {
  color: #1fa67a;
}

    body{
    margin-top:0px;
    background:#F0F8FF;
}
.card {
    margin-bottom: 1.5rem;
    box-shadow: 0 1px 15px 1px rgba(52,40,104,.08);
}
.card {
    position: relative;
    display: -ms-flexbox;
    display: flex;
    -ms-flex-direction: column;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 1px solid #e5e9f2;
    border-radius: .2rem;
}
.card-header:first-child {
    border-radius: calc(.2rem - 1px) calc(.2rem - 1px) 0 0;
}
.card-header {
    border-bottom-width: 1px;
}
.card-header {
    padding: .75rem 1.25rem;
    margin-bottom: 0;
    color: inherit;
    background-color: #fff;
    border-bottom: 1px solid #e5e9f2;
}


</style>

</head>
<script>
    //function switchRole(){

 function switchRole() {
  // (A) GET EMAIL + PASSWORD
  var data = new FormData(document.getElementById("switchForm"));
 
  // (B) AJAX REQUEST
  fetch("/api/logsAPI.php", { 
    method:"POST", 
    body:data 
   })
  .then((res) => { return res.text(); })
  .then((txt) => {
    if (txt == "OK") { 
        location.href = "home.php";
     }else { 
        alert(txt); 
    }
  });
  return false;
}



    </script>
<body>
    
    





