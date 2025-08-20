
<!-- index.php -->
<html>
<head>
    <title>Teach Me</title>
    <!-- Add Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-u1j5O6H2O+429xitn7w4GB4GBXSXcZb695L/0t36tCv0ocDGA4M4cj77uBH/CKyA" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <script src="https://kit.fontawesome.com/6b0af4bcb3.js" crossorigin="anonymous"></script>

    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f6f8;
            padding: 20px 0;
        }

        .card {
            border-radius: 10px;
        }

        @media (max-width: 576px) {
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }
        }
    </style>
    
    <script>
        async function askQuestion() {
            let userInput = document.getElementById("userInput").value;
            let responseArea = document.getElementById("response");

            let response = await fetch('/edu_search_api', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    userInput: userInput
                })
            });

            let data = await response.json();
            responseArea.value = data.choices[0].message.content;
        }
    </script>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-dark bg-dark">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">Teach Me</span>
    </div>
</nav>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-6 col-sm-12 mb-4">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div class="mb-4">
                        <label for="userInput" class="form-label">Ask a question:</label>
                        <textarea id="userInput" class="form-control" rows="5"></textarea>
                    </div>
                    <div class="mb-4 d-flex justify-content-center">
                        <button class="btn btn-primary" onclick="askQuestion()">Ask</button>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-5 col-md-6 col-sm-12">
            <div class="card shadow-lg">
                <div class="card-body">
                    <div>
                        <label for="response" class="form-label">Response:</label>
                        <textarea id="response" class="form-control" rows="10" readonly></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Bootstrap JS and Popper.js for Bootstrap components to work properly -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz5AT/Yp//v8MBylSHZ7n4ug7+UjL57X3/3vT3tuL3Nt3Mw7cmDA6j6gDq" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.2/dist/js/bootstrap.min.js" integrity="sha384-8BgfAtxTU23qztlhUzJ3qMyfp7HRroPH3R3Fnq5bOcI95zVZeA+Li4QkLlZMo1iZ" crossorigin="anonymous"></script>
</body>
</html>
