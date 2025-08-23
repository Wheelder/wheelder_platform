<?php
            //change to post method

            if (isset($_POST['ask'])) {
              
                $q = $_POST['query'];
                //$qm= $_GET['img'];
            
                // Convert $q to lowercase
                $qm = strtolower($q);

                // Define an array of words and phrases to remove, including common words
                $toRemove = array(
                    '/what is/',
                    '/what/',
                    '/whats/',
                    '/what was/',
                    '/what will/',
                    '/what can/',
                    '/what could/',
                    '/what should/',
                    '/what are/',
                    '/like/',
                    '/so/',
                    '/how/',
                    '/when/',
                    '/where/',
                    '/why/',
                    '/who/',
                    '/which/',
                    '/whom/',
                    '/whose/',
                    '/whither/',
                    '/whence/',
                    '/how many/',
                    '/how much/',
                    '/how long/',
                    '/how often/',
                    '/how far/',
                    '/how old/',
                    '/how come/',
                    '/how well/',
                    '/how many/',
                    '/\./',
                    '/\,/',
                    '/\!/',
                    '/\?/',
                    '/what\'s/i',
                    '/the/',
                    '/an/',
                    '/a/',
                    '/in/',
                    '/of/',
                    '/on/',
                    '/at/',
                    '/by/',
                    '/with/'
                );

                // Remove the defined words and phrases from $q
                $imgq = preg_replace($toRemove, ' ', $qm); // Replace removed words with a space
            
                // Remove extra spaces and punctuation
                $imgq = preg_replace('/\s+/', ' ', $imgq);
                $imgq = preg_replace('/[.,!?]/', '', $imgq);
                //$imgq = trim($qm); // Remove leading/trailing spaces
            
                
                $answer = $note->generateResponse($q);

                // Split the sentences, preserving periods at the beginning of sentences
                $sentences = preg_split('/(?<=[.?!])\s+(?=[A-Z])/', $answer);
                //$sentences = preg_replace('','', $sentences);

                $formattedAnswer = implode("<br></br>", $sentences);
                //$formattedAnswer= preg_replace('**','',$formattedAnswer);
                //$formattedAnswer= preg_replace('***','',$formattedAnswer);
                //$formattedAnswer= preg_replace('###','',$formattedAnswer);
                $formattedAnswer = trim($formattedAnswer);
               

                $status = 1;

                // Strip HTML tags from $formattedAnswer
                //$content = strip_tags($formattedAnswer);
                $content = trim($formattedAnswer);
                //$category= $_GET['category'];
                //$title= $_GET['title'];
                $option="chat";
                $deep_answer="level 1";
                $unf_answer= $answer;
                $question= $q;
                $answer= $formattedAnswer;

                $image = $note->generateImage($answer);

            
                //make the $formattedAnswer a string don't include it here the tags, just the text without losing the format
                //$note->insert_data($title, $image, $category, $content, $status);
                //$note->storeData( $q,$content,$image);
               // insert($question,$unf_answer,$answer,$deep_answer,$options,$filepath)
                $blog->insert($question,$unf_answer,$answer,$deep_answer,$option,$image );
                
                echo '<div class="col-md-6 ">
        <div class="content" id="answerDiv" class="content draggable" draggable="true"> 
        ' . $content . '
          </div>
       </div>
      <div class="col-md-6 ">
        <div class="contentImage" id="imageDiv" class="contentImage draggable" draggable="true">
            <img src=' . $image . ' alt="failed" style="width: 100%; height: 630px; object-fit: contain;"/>
        </div>
       </div>
    </div>
       <div class="row">
            <div class="col-md-12">
                <div class="searchDiv">
                    <form action="" method="get">
                        <div class="input-group">
                            <input type="text" class="form-control" id="query" name="query"
                                placeholder="Enter your query" style="width:400px;"
                                value="">

                              

                                 
                         
                            <button type="submit" class="btn btn-dark">Ask</button>
                            &nbsp;
                           

                            <a href="/whl_project/" class="btn btn-dark">Clean</a>
                        </div>

                </div>
                </form>
            </div>
         </div>
         '
        ;

        }else if (isset($_POST['deeper1'])) {
            $mainq = $_POST['deeper1'];

            $answer= $note->generateResponse($mainq);

            //$qm= $_GET['img'];

           $level= "Here I already asked this question before:".$mainq."and the provided answer is,".$answer."Make this answer one level more deeper beside keeping this answser.";
           // $level= "Here I already asked this question before:".$q."Make this answer one level more deeper.";
            $q=$level." ".$mainq;


        
            // Convert $q to lowercase
            $qm = strtolower($q);

            // Define an array of words and phrases to remove, including common words
            $toRemove = array(
                '/what is/',
                '/what/',
                '/whats/',
                '/what was/',
                '/what will/',
                '/what can/',
                '/what could/',
                '/what should/',
                '/what are/',
                '/like/',
                '/so/',
                '/how/',
                '/when/',
                '/where/',
                '/why/',
                '/who/',
                '/which/',
                '/whom/',
                '/whose/',
                '/whither/',
                '/whence/',
                '/how many/',
                '/how much/',
                '/how long/',
                '/how often/',
                '/how far/',
                '/how old/',
                '/how come/',
                '/how well/',
                '/how many/',
                '/\./',
                '/\,/',
                '/\!/',
                '/\?/',
                '/what\'s/i',
                '/the/',
                '/an/',
                '/a/',
                '/in/',
                '/of/',
                '/on/',
                '/at/',
                '/by/',
                '/with/'
            );

            // Remove the defined words and phrases from $q
            $imgq = preg_replace($toRemove, ' ', $qm); // Replace removed words with a space
        
            // Remove extra spaces and punctuation
            $imgq = preg_replace('/\s+/', ' ', $imgq);
            $imgq = preg_replace('/[.,!?]/', '', $imgq);
            //$imgq = trim($qm); // Remove leading/trailing spaces
        
            $image = $note->generateImage($imgq);
            $answer = $note->generateResponse($q);

            // Split the sentences, preserving periods at the beginning of sentences
            $sentences = preg_split('/(?<=[.?!])\s+(?=[A-Z])/', $answer);
            //$sentences = preg_replace('','', $sentences);
            $formattedAnswer = implode("<br></br>", $sentences);
            $formattedAnswer = trim($formattedAnswer);
            $status = 1;

            // Strip HTML tags from $formattedAnswer
            //$content = strip_tags($formattedAnswer);
            $content = trim($formattedAnswer);
            $option="chat";
                $deep_answer=$formattedAnswer;
                $unf_answer= $answer;
                $question= $mainq;
                $ans= $answer;

            
                //make the $formattedAnswer a string don't include it here the tags, just the text without losing the format
                //$note->insert_data($title, $image, $category, $content, $status);
                //$note->storeData( $q,$content,$image);
               // insert($question,$unf_answer,$answer,$deep_answer,$options,$filepath)
                $note->insert($question,$unf_answer,$answer,$deep_answer,$option,$image );
            echo '<div class="col-md-6 ">
    <div class="content" id="answerDiv"> 
    ' . $content . '
      </div>
   </div>
  <div class="col-md-6 ">
    <div class="contentImage" id="imageDiv">
        <img src=' . $image . ' alt="failed" style="width: 100%; height: 630px; object-fit: contain;"/>
    </div>
   </div>
</div>
   <div class="row">
        <div class="col-md-12">
            <div class="searchDiv">
                <form action="" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control" id="query" name="query"
                            placeholder="Enter your query" style="width:400px;"
                            value="">

                             
                     
                        <button type="submit" class="btn btn-dark">Ask</button>
                        &nbsp;
                       

                        <a href="/app.php" class="btn btn-dark">Clean</a>
                    </div>

            </div>
            </form>
        </div>
      </div>
      ';
    } 
    ?>