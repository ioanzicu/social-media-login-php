<?php
require_once __DIR__ . '/header.php';
require_once __DIR__ . '/app/fb_setup.php';
require_once __DIR__ . '/app/loginScript.php';
?>

<div class="container-fluid">
    <div class="row">
        <div class="centered">
            <h1>Social Media Login</h1>
            <hr />
            <?php if (isset($_SESSION['username'], $_SESSION['id'])) : ?>
            <h2>You are logged in as <?= $_SESSION['username'] ?> </h2>
            <img src="<?= $_SESSION['avatar']; ?>" alt="<?= $_SESSION['username'] ?>" width="180" height="180">

            <h2>
                <a href="logout.php" class="btn btn-link pull-right">Logout</a>
            </h2>
            <?php else : ?>
            <p>
                <a href="login.php" class="btn btn-default btn-lg btn-block">Site Login</a>
            </p>
            <h2>OR</h2>
            <p>
                <a href="<?php echo $callback_url ?>" class="btn btn-primary btn-lg btn-block">Login With Facebook</a>
            </p>
            <p>
                <a href="" class="btn btn-danger btn-lg btn-block">Login With Google</a>
            </p>

            <p>
                <a href="" class="btn github btn-lg btn-block">Login With GitHub</a>
            </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include_once 'footer.php' ?>