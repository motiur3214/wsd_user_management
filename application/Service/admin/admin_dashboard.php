<?php
session_start();

if (isset($_SESSION['user_id'])) {
    $db = Database::getInstance(); // Create Database instance
    $user = new User($db); // Pass Database instance to User constructor
    $user_admin = $user->getUserById($_SESSION['user_id']);

    $current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get page number from URL or set to 1
    $limit = 10; // Number of users per page
    $total_users = $user->countUsers();
    $offset = ($current_page - 1) * $limit;
    // Get all users
    $all_users = $user->getAllUsers($offset, $limit);

    // Handle search form submission (if submitted)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Check for POST request
        $search_text = trim($_POST['search_text']);
        $search_text = urlencode($search_text); // Encode search text before using it
        $filtered_users = $user->searchUsers($search_text); // Call searchUsers function
        $all_users = $filtered_users; // Update for displaying filtered users

    }
    $total_pages = ceil($total_users / $limit); // Calculate total pages using ceil function


} else {
    // Redirect to login page if not logged in
    header("Location:" . BASE_URL . "/login");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../application/style/admin_dashboard.css">
    <title>Admin Dashboard</title>
</head>
<body>
<header>
    <h1 style="">Welcome, <span style="color: #3e8e41"><?php echo $user_admin["name"]; ?>!</span></h1>
    <a href="../logout" class="logout">Logout</a>
</header>
<div class="container">
    <main>
        <section class="search-filter">
            <form action="" method="post">
                <div>
                    <label for="search_text">Search by Name or Email:</label>
                    <input type="text" id="search_text" name="search_text"
                           value="<?php echo($_POST['search_text'] ?? "") ?>"
                           placeholder="Enter search term">
                </div>
                <button type="submit">Search</button>
            </form>
        </section>

        <?php
        if ($all_users) {
            echo "<h2>All Users (total: $total_users)</h2>";
            echo "<table>";
            echo "<tr>";
            echo "<th>Name</th>";
            echo "<th>Email</th>";
            echo "<th>Role</th>";
            echo "</tr>";

            // Loop through all users (filtered or unfiltered) and display information in a table
            foreach ($all_users as $user_data) {

                echo "<tr>";
                echo "<td>" . $user_data["name"] . "</td>";
                echo "<td>" . $user_data["email"] . "</td>";
                if (isset($user_data["role"])) {
                    echo "<td>" . ($user_data["role"] == 2 ? 'User' : 'Admin') . "</td>"; // Display role if available, otherwise empty string
                }
                echo "</form>";
                echo "</tr>";
            }

            echo "</table>";
            if ($total_pages > 1) {
                echo "<ul class='pagination'>";
                for ($i = 1; $i <= $total_pages; $i++) {
                    $active_class = ($i == $current_page) ? "active" : "";
                    echo "<li class='$active_class'><a href='" . BASE_URL . "/admin/dashboard?page=$i'>$i</a></li>";
                }
                echo "</ul>";
            }
        } else {
            echo "No users found.";
        }
        ?>

    </main>
</div>
<footer>
    <p>&copy; 2024 Motiur Rahman</p>
</footer>
</body>
</html>
