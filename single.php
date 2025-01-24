<?php
$page = 'single';
$jsonFile = 'blog.json';
$postFound = false;
$postData = null;

if (file_exists($jsonFile)) {
    $jsonData = file_get_contents($jsonFile);
    $data = json_decode($jsonData, true);

    if (json_last_error() === JSON_ERROR_NONE) {
        if (isset($_GET['title'])) {
            $requestedTitle = trim($_GET['title']);
            $requestedTitle = filter_var($requestedTitle, FILTER_SANITIZE_STRING);

            foreach ($data as $post) {
                if (strcasecmp($post['title'], $requestedTitle) == 0) {
                    $postFound = true;
                    $postData = $post;
                    break;
                }
            }

            if (!$postFound) {
                $errorMessage = "Sorry, the requested content was not found.";
            }
        } else {
            $errorMessage = "No content specified. Please provide a title in the URL.";
        }
    } else {
        $errorMessage = "Error decoding JSON: " . json_last_error_msg();
    }
} else {
    $errorMessage = "Content file not found.";
}
$title = $postData['title'];
include_once 'header.php';
?>

<section aria-label="section">
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                <div class="blog-read">
                    <?php
                    if ($postFound && $postData) {
                        echo '<div class="post-text">';



                        echo $postData['content'];
                        echo '</div>';
                    } else {
                        echo '<div class="post-text">';
                        echo '<h2>Error</h2>';
                        echo '<p>' . htmlspecialchars($errorMessage) . '</p>';
                        echo '</div>';
                    }
                    ?>
                </div>

                <div class="spacer-single"></div>


            </div>

            <div id="sidebar" class="col-md-4">
                <div class="widget widget-post">
                    <h4>Recent Posts</h4>
                    <div class="small-border"></div>
                    <ul>
                        <?php
                        if (isset($data) && is_array($data)) {
                            foreach ($data as $recentPost) {
                                $encodedTitle = urlencode($recentPost['title']);
                                echo '<li><span class="date"><img src="images/blog.png" style="height:20px;" /> </span><a href="page.php?title=' . $encodedTitle . '">' . htmlspecialchars($recentPost['title']) . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </div>
                <div class="widget widget-text">
                    <h4>About Us</h4>
                    <div class="small-border"></div>
                    <p>
                        Riverside Water Defluoridation Consultants Limited is a Kenyan-owned company specializing in water purification solutions. Established to address the growing concern of fluoride- contaminated water, our mission is to make safe drinking water accessible to every household.
                    </p>
                </div>

            </div>
        </div>
    </div>
</section>

<?php include_once 'footer1.php'; ?>