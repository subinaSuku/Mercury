<!DOCTYPE html>
<html>
<head>
    <title></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-F3w7mX95PdgyTmZZMECAngseQB83DfGTowi0iMjiWaeVhAn4FJkqJByhZMI3AhiU" crossorigin="anonymous">

</head>
<body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-/bQdsTh/da6pkI1MST/rWKFNjaCP5gBSY4sEBT38Q/9RBh9AH40zEOg7Hlq2THRZ" crossorigin="anonymous"></script>
   
    <div class="container" style="color: #34498c">

        
            
            <div >
            
             
             
             <img src="https://mercury.itrobes.in/images/logo.jpeg"  height="100" width="100"  alt="Mercury Health Insurance" style="float:left">
         
            <h1 style="padding-top:35px;color:#34498c;padding-left:15px;">Mercury Insurance</h1><br><br>
        </div>

             <div style="background-color:#eeebe1;border-radius:10px;padding:20px;color: #34498c!important;">
             
                
                     
                        <p>Dear {{$details['username']}},<p><p>Greetings!</p>
                        <h3>Thank you for choosing Mercury Insurance!<br><br>
                        This is to notify that your {{$details['userservice']}} policy will expire in 15 days.
                         Kindly contact us for renewal.
                        </h3>  
                     
                    

                        </div>
      

        <div  style="background-color:#3060a2;padding:5px 0;margin-top:25px">
            
                    <p align="center" class="text-center my-2" style="color:#fff;padding:15px 20px;margin:0;">The policy details are as follows:  Bill Number: {{$details['bill']}}</p>
  
        </div>

        <div style="border-radius:50px;background-color:#dff5fe;margin-top:15px;">

            <h4 style="padding:20px;">In case of any queries or assistance, please call +91 9487 659 154 / Email : sujin@gmail.com </h4>

        </div>

        <h5 align="center"> Looking forward to serve you always</h5>


</div>
 

</body>
</html>