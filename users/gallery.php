<?php
include ('../nav.php');
?>
<h4 class="text-center text-secondary mb-4 mt-4">Photo Gallery</h4>
<div class="mb-4 w-75 m-auto">
    <table class="table table-bordered mt-5">
        <thead>
        <?php
        include('../config/connect.php');
        global $conn;

        if (isset($_SESSION['username'])) {
            $user_email= $_SESSION['username'];
            $user_id = $_SESSION['user_id'];
            echo $user_email . " - " . $user_id;
            /*
             * Static image gallery
             *
            $images = ['portrait.png', 'zoro.png', 'screen.jpg', 'cadillac.jpg', 'salon.jpg', 'house.jpg'];
            $count = count($images);
             */

            $fetch_images = "SELECT ID, Image_name FROM `image` WHERE `user_id`= :user_id";
            $stmt = $conn->prepare($fetch_images);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();
            $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $count = $stmt->rowCount();

            for ($i = 0; $i < $count; $i++) {
                if ($i % 4 == 0) {
                    echo '<tr>';
                }
                $image_id = $images[$i]['ID'];
                $modal_id = 'imageModal' . $image_id;

                $imageName = htmlspecialchars($images[$i]['Image_name']);
                $underscorePosition = strrpos($imageName, '_');
                $dotPosition = strrpos($imageName, '.');
                if ($underscorePosition !== false) {
                    $displayFileName = substr($imageName, 0, $underscorePosition);
                } else {
                    $displayFileName = substr($imageName, 0, $dotPosition);
                }
                $displayFileName = ucfirst($displayFileName);
                echo "
                <td>                
                    <div class='card' style='width: 18rem;'>                                       
                           <a href='#' data-bs-toggle='modal' data-bs-target='#$modal_id' data-bs-image='./uploads/$imageName'>
                              <img src='./uploads/$imageName' class='card-img' alt='...'>                              
                            </a>
                        <div class='card-body'>
                            <h5 class='card-title'>$displayFileName</h5>
                        </div>
                    </div>
                </td>";
                // Modal for each image
                echo "
                <div class='modal fade' id='$modal_id' tabindex='-1' role='dialog' 
                    aria-labelledby='imageModalLabel$image_id' aria-hidden='true'>
                    <div class='modal-dialog modal-dialog-centered modal-xl' role='document'>
                        <div class='modal-content'>
                            <div class='modal-header'>
                                <h5 class='modal-title' id='imageModalLabel$image_id'>Image Preview</h5>
                                <button type='button' class='btn-close' data-bs-dismiss='modal' 
                                    aria-label='Close'></button>
                            </div>
                            <div class='modal-body text-center'>
                                <img id='modalImage$image_id' src='./uploads/$imageName' class='img-fluid' alt='Preview'>
                                <a href='./share_image.php?shared_image=$image_id' class='btn btn-secondary' 
                                    tabindex='-1' role='button' aria-disabled='true'>Share with a friend</a>
                            </div>                            
                        </div
                    </div>
                </div>";

                if (($i + 1) % 4 == 0) {
                    echo "</tr>";
                }
            }
            if ($count % 4 != 0) {
                echo "</tr>";
            }
        } else {
            echo "<script>window.open('./signin.php', '_self')</script>";
        }
        ?>
        </thead>
    </table>
</div>

<!--Upload image to DB and locally-->
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-1">
            <h4 class="text-secondary mb-4 mt-4">Upload a Photo</h4>
            <form action="upload_image.php" method="post" enctype="multipart/form-data">
                <div class="form-outline mb-4">
                    <label for="fileToUpload" class="form-label mt-4">Select image to upload:</label>
                    <input type="file" name="fileToUpload" id="fileToUpload" class="form-control  w-50 m-auto"
                           accept="image/*">
                </div>
                <div class="form-outline mb-4">
                    <input type="submit" value="Upload Image" name="upload_image" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>
<hr>
<!--Upload multiple images to share with friends-->
<?php
$select_friends = "SELECT * FROM `request` WHERE 
                   (Sender_id = :user_id AND Req_status = 'Accepted') 
                   OR (Receiver_id = :user_id AND Req_status = 'Accepted')";
$stmt_friends = $conn->prepare($select_friends);
$stmt_friends->bindParam(':user_id', $user_id);
$stmt_friends->execute();
$friends = $stmt_friends->fetchAll(PDO::FETCH_ASSOC);
// Initializing an array to hold friend IDs
$friend_ids = [];
foreach ($friends as $friend) {
    if ($friend['Sender_id'] == $user_id) {
        $friend_ids[] = $friend['Receiver_id'];
    } else {
        $friend_ids[] = $friend['Sender_id'];
    }
}
// Storing the friends array in the session
$_SESSION['friend_ids'] = $friend_ids;
//print_r($_SESSION['friend_ids']);
?>
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-1">
            <h4 class="text-secondary mb-4 mt-4">Upload and Share Photos</h4>
            <form action="uploadShare.php" method="post" enctype="multipart/form-data">
                <div class="form-outline mb-4">
                    <label for="imagesToUpload" class="form-label mt-4">
                        Select all the images to upload and share with friends</label>
                    <input type="file" name="imagesToUpload[]" id="imagesToUpload" class="form-control  w-50 m-auto"
                           accept="image/*" multiple>
                </div>
                <h5>Share with</h5>
                <div class="form-outline mb-3">
                    <select name="friend_id" id="friendSelect" class="form-select form-select-sm w-50">
                        <option selected disabled>Select friend</option>
                        <?php
                        foreach ($friend_ids as $friend_id) {
                            //Fetching user email for each friend
                            $select_user = "SELECT * FROM `user` WHERE `ID` = :friend_id";
                            $stmt_user = $conn->prepare($select_user);
                            $stmt_user->bindParam(':friend_id', $friend_id);
                            $stmt_user->execute();
                            $friend_details = $stmt_user->fetch(PDO::FETCH_ASSOC);
                            echo "<option value='{$friend_details['ID']}'>{$friend_details['Email']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-outline mb-4">
                    <input type="submit" value="Share Images" name="share_multi_images" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>

<hr>
<!--Download image locally, from drive-->
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-1">
            <h4 class="text-secondary mb-4 mt-4">Download a Photo locally</h4>
            <form action="download_image.php" method="post" enctype="multipart/form-data">
                <div class="form-outline mb-4">
                    <label for="fileToDownload" class="form-label mt-4">Select image to download:</label>
                    <input type="file" name="fileToDownload" id="fileToDownload" class="form-control  w-50 m-auto">
                </div>
                <div class="form-outline mb-4">
                    <input type="submit" name="download_it" value="Download Image" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>
<hr>
<!--Download image from database-->
<div class="container">
    <div class="row">
        <div class="col-md-8 offset-md-1">
            <form action="download_image_db.php" method="post">
                <div class="form-outline mb-4">
                    <h4 class="text-secondary mb-4 mt-4">Download a Photo from database</h4>
                    <select name="file_id" id="fileSelect" class="form-select form-select-sm w-50">
                        <option selected disabled>Select image to download</option>
                        <?php
                        foreach ($images as $image) {
                            echo "<option value='{$image['ID']}'>{$image['Image_name']}</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-outline mb-4">
                    <input type="submit" name="download_it_db" value="Download Image" class="btn btn-primary">
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>

<script>
    /*
    Code Source inspired from:
    https://www.freecodecamp.org/news/how-to-build-a-modal-with-javascript
    https://stackoverflow.com/questions/19279629/bootstrap-jquery-show-bs-modal-event-wont-fire
     */
    document.addEventListener('DOMContentLoaded', function () {
        var imageModal = document.getElementById('imageModal');
        imageModal.addEventListener('show.bs.modal', function (event) {  //Event Listener for showing #imageModal id
            var button = event.relatedTarget;   // Button that triggers the modal, <a> when clicked, using jQuery object
            var imageSrc = button.getAttribute('data-bs-image');    // Extract image source
            var modalImage = imageModal.querySelector('#modalImage');
            modalImage.src = imageSrc;
        });
    });
</script>
</body>
</html>


<!-- static gallery
<div class='card' style='width: 18rem;'>
                           <a href='#' data-bs-toggle='modal' data-bs-target='#imageModal' data-bs-image='./uploads/{$images[$i]}'>
                            <img src='./uploads/{$images[$i]}' class='card-img' alt='...'>
                         </a>
                        <div class='card-body'>
                            <h5 class='card-title'>{$images[$i]}</h5>
                        </div>
                    </div>
-->