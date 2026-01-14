<?php
session_start();
$path = $_SERVER['DOCUMENT_ROOT'];
include $path . '/apps/work/ui/layouts/nav.php';
?>

<style>
  .chatbtn {
    display: none;
  }
</style>

<section class="d-block py-60 upload-profile-box">
  <div class="container-lg">
    <div class="d-block rounded-3 bg-white p-4">
      <form>
        <div class="row gy-3 mb-3 order-md-1">
          <div class="col-12">
            <h2 class="text-black fs-20 text-uppercase fw-600">
              Create Invitation Link
            </h2>
          </div>
        </div>
        <div class="row gy-3">
          <div class="col-md-8">
            <div class="d-flex flex-column gap-1">
        
              <?php
              //get the base url


              
              $uri = $_SERVER['HTTP_HOST'];

              $link = $uri . '/apps/work/ui/views/auth/signup.php?ref=' . $_SESSION['user_id'];
              ?>
              <div class="input-group">
                <input type="text" name="link" id="link" value="<?php echo $link; ?>" class="form-control form-control-md" readonly>
              </div>
            </div>
          </div>
          <div class="col-md-4 align-self-end">
            <button type="button" class="btn btn-primary btn-sm" onclick="shareLink()">Share to Earn</button>
          </div>
        </div>
      </form>
    </div>
  </div>
</section>

<script>
  function shareLink() {
    var link = document.getElementById("link").value;
    var shareText = "Join me and earn rewards! Use my invitation link: " + link;
    if (navigator.share) {
      navigator.share({
        title: document.title,
        text: shareText,
        url: link
      })
      .then(() => {
        console.log("Link shared successfully");
      })
      .catch((error) => {
        console.error("Error sharing link:", error);
      });
    } else {
      alert("Copy and share the link manually: " + link);
    }
  }
</script>

<?php
include $path . '/apps/work/ui/layouts/footer.php';
?>
