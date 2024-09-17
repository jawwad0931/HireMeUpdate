<?php
error_reporting(0);
session_start();

$user_id = $_SESSION['id'];
// echo $user_id;


if($_GET['id']){
  $payment_status_id = $_GET['id'];
//   echo $payment_status_id;  
}

include 'config/db_connection.php';

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Ensure the user is logged in
if (!isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Fetch the discounted total from the session
$discountedTotal = isset($_SESSION['discounted_total']) ? $_SESSION['discounted_total'] : 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HireMe</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <?php include 'includes/header.php'; ?>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
        .centered-form {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh; /* Center vertically in viewport height */
        }
        /* for payment status */
        @keyframes pulse {
        0% {
            transform: scale(1);
            box-shadow: 0 0 0 rgba(0, 0, 0, 0.2);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 10px rgba(255, 0, 0, 0.5);
        }
        100% {
            transform: scale(1);
            box-shadow: 0 0 0 rgba(0, 0, 0, 0.2);
        }
        }

        .animated-button {
        animation: pulse 1s infinite;
        }
    </style>
</head>
<body class="bg-white">
<div class="container centered-form">
    <div class="w-100 border p-3" style="max-width: 450px;">
        <!-- Display any success message -->
        <?php 
            if(isset($_SESSION['success'])) {
                echo "<h3 class='text-success'>" . $_SESSION['success'] . "</h3>";
                unset($_SESSION['success']);
            }
        ?>
        <!-- navbar start -->
        <h2 class="mb-4 text-start text-primary">Complete Your Payment</h2>
        <hr>    
        <form action="charge.php" method="post" id="payment-form">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="name" class="text-primary">Full Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter your full name" required>
                </div> 
                <div class="form-group col-md-6">
                    <label for="email" class="text-primary">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email address" required>
                </div>
                
                <div class="form-group col-md-12">
                    <label for="amount" class="text-primary">Amount</label>
                    <input type="number" step="0.01" id="amount" name="amount" class="form-control" placeholder="Enter amount" value="<?php echo htmlspecialchars($discountedTotal); ?>">
                </div>

                <div class="form-group col-md-0">
                    <!-- <label for="amount" class="text-primary">Your Id</label> -->
                    <input type="hidden" id="user-id" name="user_id" value="<?php echo htmlspecialchars($_SESSION['id']); ?>">               
                 </div>
            </div>

            <div class="form-row">
                <label for="card-element" class="col-12 text-primary">
                    Credit or Debit Card
                </label>
                <div id="card-element" class="form-control">
                    <!-- A Stripe Element will be inserted here. -->
                </div>
                <!-- Used to display form errors. -->
                <div id="card-errors" role="alert" class="text-danger mt-2"></div>
            </div>

            <button type="submit" class="btn btn-primary btn-sm mt-4">Submit Payment</button>
            <div class="card bg-light my-3 p-2">
                <span class="text-danger">Notice</span>
                <p class="text-danger">If you complete your payment please click on here to confirm</p>
                <a href="update_payment_status.php?id=<?php echo urlencode($payment_status_id); ?>" class="btn  btn-sm mt-2 ml-0 w-50 animated-button" id="updatePaymentButton">Update Payment Status</a>
            </div>
        </form>
    </div>
</div>

<script>
    var stripe = Stripe('pk_test_51PoAkk2LBNlbJVWrG2rYTdhHRHVDovQZDrIIC8fmrQU1Tcp9nh6Wl5xJFFTgYWplph1ZhsWYu9gCSkdaGtkNBjMF00NbMESEJv'); // Replace with your actual Publishable Key
    var elements = stripe.elements();
    var card = elements.create('card');
    card.mount('#card-element');

    card.on('change', function(event) {
        var displayError = document.getElementById('card-errors');
        if (event.error) {
            displayError.textContent = event.error.message;
        } else {
            displayError.textContent = '';
        }
    });

    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
        event.preventDefault();

        stripe.createToken(card).then(function(result) {
            if (result.error) {
                // Inform the user if there was an error.
                var errorElement = document.getElementById('card-errors');
                errorElement.textContent = result.error.message;
            } else {
                // Send the token to your server.
                stripeTokenHandler(result.token);
            }
        });
    });

    function stripeTokenHandler(token) {
        var form = document.getElementById('payment-form');
        var hiddenInput = document.createElement('input');
        hiddenInput.setAttribute('type', 'hidden');
        hiddenInput.setAttribute('name', 'stripeToken');
        hiddenInput.setAttribute('value', token.id);
        form.appendChild(hiddenInput);

        // Submit the form
        form.submit();
    }


</script>
</body>
</html>