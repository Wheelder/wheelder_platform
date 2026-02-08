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

                // Strip markdown symbols so the answer reads as clean plain text
                $content = str_replace(array('###', '##', '#', '***', '**', '*', '---', '`'), '', $formattedAnswer);
                $content = trim($content);
                //$category= $_GET['category'];
                //$title= $_GET['title'];
                $option="chat";
                $deep_answer="level 1";
                $unf_answer= $answer;
                $question= $q;
                $answer= $formattedAnswer;

                // Use original question as image prompt — Pollinations handles natural language well
                $image = $note->generateImage($q);

                // Generate a unique session_id for this new conversation thread
                // All deeper clicks will reuse this same session_id to group entries together
                $sessionId = uniqid('conv_', true);
                $_POST['session_id'] = $sessionId;

                // Store the initial Q&A in the conversations table (depth 0 = first ask)
                $note->storeConversation($sessionId, $q, $content, $image, 0);

                // Pass the answer back so the deeper button can chain from it
                $_POST['prev_answer'] = $unf_answer;

                //make the $formattedAnswer a string don't include it here the tags, just the text without losing the format
                //$note->insert_data($title, $image, $category, $content, $status);
                //$note->storeData( $q,$content,$image);
               // insert($question,$unf_answer,$answer,$deep_answer,$options,$filepath)
                //$blog->insert($question,$unf_answer,$answer,$deep_answer,$option,$image );
                
                // Layout: text LEFT, image RIGHT
                echo '<div class="col-md-6">
                        <div class="content" id="answerDiv">' . $content . '</div>
                      </div>
                      <div class="col-md-6">
                        <div class="contentImage" id="imageDiv">
                            <img src="' . $image . '" alt="Generated image"/>
                        </div>
                      </div>';

        }else if (isset($_POST['deepen'])) {
            // Unified handler for all depth levels (deeper1 through deeper7)
            // Each click deepens the previous answer by one degree
            $depthLevel = (int)($_POST['depth_level'] ?? 1);
            $mainq = $_POST['original_question'] ?? '';
            $prevAnswer = $_POST['prev_answer'] ?? '';

            // Safety: clamp depth to 1-7 range
            if ($depthLevel < 1) $depthLevel = 1;
            if ($depthLevel > 7) $depthLevel = 7;

            // If no previous answer yet (first deeper click after Ask), generate the base answer first
            if (empty($prevAnswer)) {
                $prevAnswer = $note->generateResponse($mainq);
            }

            // Build the "go deeper" prompt — tells the AI to deepen the previous answer by one level
            $deeperPrompt = "Here I already asked this question before: " . $mainq
                . " and the provided answer is: " . $prevAnswer
                . " Make this answer one level more deeper (depth level " . $depthLevel . " of 7)"
                . " beside keeping this answer. Add more detail, examples, and nuance.";

            // Generate the deeper answer and a relevant image
            $answer = $note->generateResponse($deeperPrompt);
            $image = $note->generateImage($mainq);

            // Split sentences for readable formatting
            $sentences = preg_split('/(?<=[.?!])\s+(?=[A-Z])/', $answer);
            $formattedAnswer = implode("<br></br>", $sentences);
            $formattedAnswer = trim($formattedAnswer);

            // Strip markdown symbols so the answer reads as clean plain text
            $content = str_replace(array('###', '##', '#', '***', '**', '*', '---', '`'), '', $formattedAnswer);
            $content = trim($content);

            // Reuse the session_id from the Ask or previous Deepen so all entries stay grouped
            $sessionId = $_POST['session_id'] ?? uniqid('conv_', true);
            $_POST['session_id'] = $sessionId;

            // Store the deepened Q&A in the conversations table under the same session
            $note->storeConversation($sessionId, $mainq, $content, $image, $depthLevel);

            // Also store in the legacy ans_data table (existing behaviour)
            $note->storeData($mainq, $content, $image);

            // Pass the latest answer back to record.php so the NEXT deeper button can use it
            // This is how the chain works: Ask → deeper1 → deeper2 → ... → deeper7
            $_POST['prev_answer'] = $answer;
            $_POST['original_question'] = $mainq;

            // Pass depth level to record.php so it can show the badge outside the panels
            $_POST['current_depth_label'] = $depthLevel;

            // Layout: text LEFT (scrollable panel), image RIGHT (image panel)
            echo '<div class="col-md-6">
                    <div class="content" id="answerDiv">' . $content . '</div>
                  </div>
                  <div class="col-md-6">
                    <div class="contentImage" id="imageDiv">
                        <img src="' . $image . '" alt="Generated image"/>
                    </div>
                  </div>';
    } 
    ?>