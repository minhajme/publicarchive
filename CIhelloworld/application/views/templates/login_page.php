<html>
<head>
    <title><?php echo $page_title ? $page_title : 'CodeIgniter Tutorial' ?></title>
</head>
<body>

<h1><?php echo $title; ?></h1>
<div>
    <?php echo validation_errors(); ?>

    <?php form_open('auth/login'); ?>
    <label>Username</label><input type="text" name="username">
    <label>Password</label><input type="text" name="password">
    <input type="submit" name="login" value="login">
    </form>
</div>