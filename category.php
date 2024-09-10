<?php
include 'db.php'; // Koneksi database

// Cek apakah ID kategori ada di URL
if (isset($_GET['id'])) {
    $category_id = intval($_GET['id']); // Pastikan ID kategori adalah integer

    // Ambil nama kategori dan postingan berdasarkan ID kategori
    $categoryQuery = "SELECT * FROM categories WHERE id = $category_id";
    $categoryResult = mysqli_query($conn, $categoryQuery);

    // Cek apakah kategori ditemukan
    if (mysqli_num_rows($categoryResult) > 0) {
        $category = mysqli_fetch_assoc($categoryResult);

        $postsQuery = "SELECT * FROM posts WHERE category_id = $category_id ORDER BY created_at DESC";
        $postsResult = mysqli_query($conn, $postsQuery);
    } else {
        echo "Category not found!";
        exit;
    }
} else {
    echo "No category ID provided!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts in <?php echo htmlspecialchars($category['name']); ?></title>
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
                            echo "<li><a href='category.php?id={$category['id']}'>{$category['name']}</a></li>";
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

            <!-- Blog Post List -->
            <main class="col-md-8">
               
                <?php
                // Tampilkan postingan dalam kategori
                if (mysqli_num_rows($postsResult) > 0) {
                    while ($post = mysqli_fetch_assoc($postsResult)) {
                        echo "
                        <div class='mb-4'>
                            <h3 class='fw-bold'>{$post['title']}</h3>
                            <p class='text-muted'>Posted on {$post['created_at']}</p>
                            <p>" . substr($post['content'], 0, 150) . "...</p>
                            <a href='post.php?id={$post['id']}' class='btn btn-primary'>Read More</a>
                        </div>
                        ";
                    }
                } else {
                    echo "<p>No posts found in this category.</p>";
                }
                ?>
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
