


  
    <div class="container mt-2">
       
        <div class="row mt-1">
            <div class="col-md-12 ">
                <h2>Ask your question</h2>
                <hr>
            </div>

        <div class="row">
      
                
            <div class="col-md-12 ">
           
                <div class="searchDiv">

                    <form action="" method="post">
                        
                           
                            <textarea  name="query"  rows="5" cols="153"><?php echo $_POST['query'] ?? null; ?></textarea>
                            <hr></br>
                           
                            <button type="submit" class="btn btn-dark" name="ask">Ask</button>
                            &nbsp;
                            <button type="reset" class="btn btn-dark" >Clear</button>
                           
                        
                     </form>
                     &nbsp;
                 </div>
                 
                     <form action="" method="post">
                        
                                
                        <input type="hidden" class="form-control" id="deeper1" name="deeper1" value="<?php echo $_POST['query'] ?? null; ?>">

                        <button type="submit" class="btn btn-dark">Circular Search</button>
                        
                    
                    </form>

                
                </div>
               
            </div>

        </div>
   
        <div class="container mt-2">
        <div class="row mt-1">
    
            <?php include "app_src.php";?>

        </div>
      
    </div>

  

    <!-- Include Bootstrap JS and jQuery -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"
        integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js"
        integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V"
        crossorigin="anonymous"></script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js"
        integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.9.4/dist/Chart.min.js"
        integrity="sha384-zNy6FEbO50N+Cg5wap8IKA4M/ZnLJgzc6w2NqACZaK0u0FXfOWRRJOnQtpZun8ha"
        crossorigin="anonymous"></script>

    <!-- JavaScript Code -->
     <script src="app.js"></script>

    
   

