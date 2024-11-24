<?php
  session_start();

  // If user is logged in, only then user can view the dashboard.php page, else redirect to homepage.
  if (isset($_SESSION["user_id"])) {
    require "includes/database_connect.php";
    if (!$con) {
      echo "Couldn't connect to database!\n";
      echo mysqli_connect_error();
    } else {
      $user_id = $_SESSION["user_id"];
      
      // Fetch user details
      $sql_query = "SELECT * FROM users WHERE id='$user_id';";
      $result = mysqli_query($con, $sql_query);
      if (!$result) {
        echo "Couldn't Authenticate User!";
        echo mysqli_error($con);
      } else {
        $row = mysqli_fetch_assoc($result);
        $full_name = $_SESSION["full_name"];
        $email = $row["email"];
        $phone = $row["phone"];
        $college = $row["college_name"];
        
        // Fetch liked properties
        $sql_liked_property = "SELECT * FROM interested_users_properties INNER JOIN properties 
                               ON interested_users_properties.property_id = properties.id WHERE user_id=$user_id;";
        $liked_property_result = mysqli_query($con, $sql_liked_property);
        if (!$liked_property_result) {
          echo mysqli_error($con);
        }

        // Fetch user bookings
        $sql_bookings = "SELECT  properties.name, properties.address, booked_properties.booking_date 
          FROM 
              booked_properties 
          INNER JOIN 
              properties 
          ON 
              booked_properties.property_id = properties.id 
          WHERE 
              booked_properties.user_id = $user_id";
        $booking_result = mysqli_query($con, $sql_bookings);
        if (!$booking_result) {
          echo mysqli_error($con);
        }
      }
    }
  } else {
    header("location: index.php");
    exit();
  }
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Best PG's in Mumbai | PG Life</title>

    <link href="css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://use.fontawesome.com/releases/v5.11.2/css/all.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,600;0,700;0,800;1,300;1,400;1,600;1,700;1,800&display=swap" rel="stylesheet" />
    <link href="css/common.css" rel="stylesheet" />
    <link href="css/property_list.css" rel="stylesheet" />
    <link href="css/dashboard.css" rel="stylesheet" />
</head>

<body>
    <!-- Header Section -->
    <?php require "./includes/header.php"; ?>

    <div id="loading"></div>

    <nav aria-label="breadcrumb">
        <ol class="breadcrumb py-2">
            <li class="breadcrumb-item">
                <a href="index.php">Home</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Dashboard
            </li>
        </ol>
    </nav>

    <div class="container my-5">
        <div class="row mt-5">
            <div class="col-sm-5 offset-sm-2">
                <h1>My Profile</h1>
            </div>
        </div>
        <div class="row justify-content-sm-center">
            <div class="col-sm-3 img-row">
                <img src="./img/user.png" alt="User Image" class="usr-img">
            </div>
            <div class="col-sm-5 usr-details">
                <p><?php echo htmlspecialchars($full_name); ?></p>
                <p><?php echo htmlspecialchars($email); ?></p>
                <p><?php echo htmlspecialchars($phone); ?></p>
                <p><?php echo htmlspecialchars($college); ?></p>
            </div>
        </div>
        <div class="row justify-content-sm-end">
            <div class="col-3 offset-9">
                <a href="#" class="delete-profile-link" id="del-link">Delete Profile</a>
            </div>
        </div>
    </div>

    <!-- Booked Properties Section -->
    <div class="page-container">
        <div class="property-heading">
            <h2>My Booked Properties:</h2>
            <hr>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Property Name</th>
                        <th>Address</th>
                        <th>Booking Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Fetch booked properties for the logged-in user
                    if (isset($_SESSION['user_id'])) {
                        $user_id = $_SESSION['user_id'];
                        $query = "SELECT properties.name, properties.address, booked_properties.booking_date 
                                  FROM booked_properties
                                  INNER JOIN properties ON booked_properties.property_id = properties.id 
                                  WHERE booked_properties.user_id = $user_id";

                        $result = mysqli_query($con, $query);

                        if ($result && mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_assoc($result)) {
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['address']); ?></td>
                                    <td><?php echo htmlspecialchars($row['booking_date']); ?></td>
                                </tr>
                                <?php
                            }
                        } else {
                            echo '<tr><td colspan="3" class="text-center">No bookings found.</td></tr>';
                        }
                    } else {
                        echo '<tr><td colspan="3" class="text-center">Please log in to view your bookings.</td></tr>';
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer -->
    <?php require "./includes/footer.php"; ?>

    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/dashboard.js"></script>
</body>

</html>

<?php mysqli_close($con); ?>
