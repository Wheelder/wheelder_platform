<?php
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/lib/controllers/BlogController.php';
$blog = new BlogController();

//$blog->checkSessionAndRedirect();

include $path.'/apps/lib/ui/layouts/nav.php';


?>

<div id="popup" class="popup-hidden">
  <span id="popup-message"></span>
</div>
<style>
  .popup-hidden {
  display: none;
  /* other styles */
}

.popup-visible {
  display: block;
  position: fixed;
  bottom: 10%;
  left: 50%;
  transform: translate(-50%, -50%);
  background-color: #4CAF50;
  color: white;
  padding: 16px;
  z-index: 1;
  text-align: center;
  border-radius: 4px;
}

.loading-splash {
    display: none;
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    z-index: 9999;
}

.loader {
    border: 5px solid #f3f3f3;
    border-top: 5px solid #3498db;
    border-radius: 50%;
    width: 50px;
    height: 50px;
    animation: spin 2s linear infinite;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}


  </style>
<div class="container-fluid d-flex">
<div id="loadingSplash" class="loading-splash">
    <div class="loader"></div>
</div>


  <?php
  if ($role == "Client") {
      include 'usermap.php';
  } else {
      $queries = ["Php", "Javascript", "Reactjs","Vuejs","Python","chatgpt","AWS","Java","Django","Flask","Nodejs","Expressjs","Android","Ios","Flutter","React Native","Nextjs","Nuxtjs","Spring","Springboot","Springmvc","Restfulapi","Restapi","Graphql"];
      $limit = $_GET['limit'] ?? 20;
    
      ?>
    <div class="nav flex-column nav-pills me-5 shadow radius-4 overflow-auto" id="v-pills-tab" role="tablist" aria-orientation="vertical" style="max-height: 80vh; overflow: auto;">

        <?php
        foreach ($queries as $index => $query) {
            $isActive = ($index === 0) ? 'active' : '';
            echo '<a class="nav-link ' . $isActive . '" id="v-pills-' . $query . '-tab" data-bs-toggle="pill" href="#v-pills-' . $query . '" role="tab" aria-controls="v-pills-' . $query . '" aria-selected="true">' . ucfirst($query) . '</a>';
        }
        ?>
      </div>

      <div class="tab-content" id="v-pills-tabContent">
        <?php
        foreach ($queries as $index => $query) {
            $isActive = ($index === 0) ? 'show active' : '';
            echo '<div class="scrollable-content tab-pane fade ' . $isActive . '" id="v-pills-' . $query . '" role="tabpanel" aria-labelledby="v-pills-' . $query . '-tab">';
            $bid->feeder_home($query, $limit);
            echo '</div>';
        }
        ?>
      </div>
      <!-- Add the dropdown select element -->
      <div class="mb-3">
        <h5><a href="/apps/work/services/elite" target="_self" class="btn btn-primary">Elite</a> 
        <a href="/apps/work/services/history" target="_self" class="btn btn-primary">History</a> </h5>
        <label for="limitSelect" class="form-label">Select Limit:</label>
        <select class="form-select" id="limitSelect" name="limit" onchange="updateLimit()">
          <option value="10" <?php if ($limit == 10) echo 'selected'; ?>>10</option>
          <option value="20" <?php if ($limit == 20) echo 'selected'; ?>>20</option>
          <option value="30" <?php if ($limit == 30) echo 'selected'; ?>>30</option>
          <option value="50" <?php if ($limit == 50) echo 'selected'; ?>>50</option>
          <option value="70" <?php if ($limit == 70) echo 'selected'; ?>>70</option>
          <option value="100" <?php if ($limit == 100) echo 'selected'; ?>>100</option>
          <!-- Add more options as needed -->
        </select>
      </div>
  </div>

  <!-- Include Bootstrap JS and Your Own Scripts -->
  <!-- Bootstrap 5.1 JS Bundle with Popper -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous"></script>

  <script>

function confirmAndMakeManyBids() {
    var result = confirm("Are you sure you want to proceed?");
    if (result) {
        // User pressed OK
        makeManyBids();
    } else {
        // User pressed Cancel
        console.log("Action canceled!");
    }
}




    function showPopupM(message) {
    alert(message);  // Replace with your own popup implementation if you have one
}

function showPopup(message) {
  const popup = document.getElementById("popup");
  const popupMessage = document.getElementById("popup-message");
  
  popupMessage.innerHTML = message;
  popup.className = "popup-visible";
  
  setTimeout(() => {
    popup.className = "popup-hidden";
  }, 1000); // Hide after 3 seconds
}

async function makeSingleBid(p) {
    let url = "/apps/work/api/autowork.php?task=mbid&p=" + p;
    
    try {
        let response = await fetch(url, {
            method: "GET",
            headers: {
                // Add any necessary headers here, like authentication tokens
            }
        });

        if (response.ok) {
            let jsonResponse = await response.json();
            if (jsonResponse.status === 200) {
                showPopup("Bid Success: " + jsonResponse.message);
            } else {
                showPopup("Bid Failed: " + jsonResponse.error);
            }
        } else {
            showPopup("Network response was not ok");
        }
    } catch (error) {
        showPopup("Fetch Error: " + error);
    }
}

function makeManyBids() {
    const multi_bid_url = "/mbid?task=manybid"; // Your API endpoint for multiple bids

    // Show the loading splash
  //  document.getElementById('loadingSplash').style.display = 'block';

    fetch(multi_bid_url)
    .then(response => response.json())
    .then(data => {
        if (data.status === 200) {
            showPopup("MultiBid Success: " + data.message);
        } else {
            showPopup("MultiBid Failed: " + data.message);
        }
        
        // Hide the loading splash after showing the popup
        //document.getElementById('loadingSplash').style.display = 'none';
    })
    .catch((error) => {
        // Show the popup first
        showPopup("MultiBid Failed: Fetch Error");

        // Hide the loading splash even if there's an error
      //  document.getElementById('loadingSplash').style.display = 'none';
        
        console.error('Fetch Error:', error);
    });
}




</script>
  <script>
    function updateLimit() {
      const limitValue = document.getElementById('limitSelect').value;
      window.location.href = `?limit=${limitValue}`;
    }

    const tabLinks = document.querySelectorAll('.nav-link');
    tabLinks.forEach(link => {
      link.addEventListener('click', (e) => {
        e.preventDefault();
        tabLinks.forEach(l => l.classList.remove('active'));
        link.classList.add('active');
        const target = link.getAttribute('href');
        document.querySelectorAll('.tab-pane').forEach(tab => {
          tab.classList.remove('show', 'active');
        });
        document.querySelector(target).classList.add('show', 'active');
      });
    });
  </script>
</div>
<?php
}
include $path.'/apps/work/ui/layouts/footer.php';
?>