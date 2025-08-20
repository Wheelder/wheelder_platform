<?php
// Function to generate a random subject using English alphabet
//wget -O /dev/null https://wheeleder.com/auto_send.php
function getRandomSubject() {
    $alphabet = 'abcdefghijklmnopqrstuvwxyz'; // English alphabet in lowercase
    $subject = '';
    $subjectLength = rand(5, 10); // Random subject length between 5 and 10 characters

    for ($i = 0; $i < $subjectLength; $i++) {
        $randomIndex = rand(0, strlen($alphabet) - 1);
        $char = $alphabet[$randomIndex];

        // If it's the first character or the previous character is a space, capitalize it
        if ($i === 0 || $subject[$i - 1] === ' ') {
            $char = strtoupper($char);
        }

        $subject .= $char;
    }

    return $subject;
}

// Function to send an email with specified recipient and sender
function sendEmails($to, $from) {
    // Set up email parameters
    //$subject = getRandomSubject(); // Get a random subject
    $subject= "Its urgent!!";
    $message = "Are you there?";

    // Additional headers (optional)
    $headers = "From: $from\r\n";
    $headers .= "Reply-To: $from\r\n";

    // Send the email
    $mailSent = mail($to, $subject, $message, $headers);

    if ($mailSent) {
        echo "Email sent successfully from $from to $to.<br>";
    } else {
        echo "Email sending failed from $from to $to.<br>";
    }
}

// Call the sendEmails function with specific recipient and sender
$recipient = "juro2501@gmail.com"; // Specify the recipient email
$sender = "lkev1219@gmail.com"; // Specify the sender email
sendEmails($recipient, $sender);

?>
