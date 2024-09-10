<?php
include 'db.php'; // Koneksi database

// Cek apakah ID postingan ada di URL
if (isset($_GET['id'])) {
    $post_id = $_GET['id'];

    // Ambil detail posting berdasarkan ID
    $postQuery = "SELECT posts.*, categories.name AS category_name 
                  FROM posts 
                  JOIN categories ON posts.category_id = categories.id 
                  WHERE posts.id = $post_id";
    $postResult = mysqli_query($conn, $postQuery);

    // Cek apakah postingan ditemukan
    if (mysqli_num_rows($postResult) > 0) {
        $post = mysqli_fetch_assoc($postResult);
    } else {
        echo "Post not found!";
        exit;
    }
} else {
    echo "No post ID provided!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Header -->
    <header class="bg-primary text-white text-center py-4">
        <?php
        // Ambil pengaturan dari tabel settings
        $settingsQuery = "SELECT * FROM settings LIMIT 1";
        $settingsResult = mysqli_query($conn, $settingsQuery);
        $settings = mysqli_fetch_assoc($settingsResult);
        ?>
        <h1><?php echo $settings['title']; ?></h1>
        <p><?php echo $settings['slogan']; ?></p>
    </header>

    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar -->
            <aside class="col-md-4">

                <div class="p-4 bg-light rounded">
                    <h4>About Me</h4>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                </div>

                <div class="p-4 mt-3 bg-light rounded">
                    <h4>Categories</h4>
                    <ul class="list-unstyled">
                        <?php
                        // Ambil kategori dari tabel categories
                        $categoriesQuery = "SELECT * FROM categories";
                        $categoriesResult = mysqli_query($conn, $categoriesQuery);
                        while ($category = mysqli_fetch_assoc($categoriesResult)) {
                            echo "<li><a href='#'>{$category['name']}</a></li>";
                        }
                        ?>
                    </ul>
                </div>

                <div class="p-4 mt-3 bg-light rounded">
                    <h4>Recent Posts</h4>
                    <ul class="list-unstyled">
                        <?php
                        // Ambil postingan terbaru dari tabel posts
                        $recentPostsQuery = "SELECT id, title FROM posts ORDER BY created_at DESC LIMIT 5";
                        $recentPostsResult = mysqli_query($conn, $recentPostsQuery);
                        while ($recentPost = mysqli_fetch_assoc($recentPostsResult)) {
                            echo "<li><a href='post.php?id={$recentPost['id']}'>{$recentPost['title']}</a></li>";
                        }
                        ?>
                    </ul>
                </div>
            </aside>

            <!-- Blog Post Detail -->
            <main class="col-md-8">
                <div class="mb-4">
                    <h2 class="fw-bold"><?php echo $post['title']; ?></h2>
                    <p class="text-muted">Category: <?php echo $post['category_name']; ?> | Posted on <?php echo $post['created_at']; ?></p>
                    <p><?php echo $post['content']; ?></p>
                </div>

                <a href="index.php" class="btn btn-primary">Back to Home</a>
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-white text-center py-4 mt-4">
        <p><?php echo $settings['footer']; ?></p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Tutup koneksi database
mysqli_close($conn);
?>
