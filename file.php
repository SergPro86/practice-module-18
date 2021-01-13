<?php

require 'config.php';

$errors = [];
$messages = [];

$imageFileName = $_GET['name'];
$commentFilePath = COMMENT_DIR . '/' . $imageFileName . '.txt';

if (!empty($_POST['comment'])) {
    
    $comment = trim($_POST['comment']);

    if($comment === '') {
        $errors[] = 'Вы не ввели текст комментария';
    }

    if (empty($errors)) {
        $comment = strip_tags($comment);
        $comment = str_replace(array(["\r\n","\r","\n","\\r","\\n","\\r\\n"]),"<br/>",$comment);
        $comment = date('d.m.y H:i') . ': ' . $comment;

        file_put_contents($commentFilePath,  $comment . "\n", FILE_APPEND);
        $messages[] = 'Комментарий был добавлен';
    }

}

$comments = file_exists($commentFilePath)
    ? file($commentFilePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
    : [];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Галерея изображений | Файл <?php echo $imageFileName; ?></title>
</head>
<body>
    <div class="container pt-4">
        <h1 class="mb-4"><a href="<?php echo URL;?>">Галерея изображений</a></h1>

        <?php foreach ($errors as $error) : ?>
            <div class="alert alert-danger"><?php echo $error;?></div>
        <?php endforeach; ?>

        <?php foreach ($messages as $message) : ?>
            <div class="alert alert-succes"><?php echo $message;?></div>
        <?php endforeach; ?>

        <h2 class="mb-4">Файл <?php echo $imageFileName; ?></h2>

        <div class="row">
        <div class="col-12 col-sm-8 offset-sm-2">

            <img src="<?php echo URL . '/' . UPLOAD_DIR . '/' . $imageFileName ?>" class="img-thumbnail mb-4"
                 alt="<?php echo $imageFileName ?>">

            <h3>Комментарии</h3>
            <?php if(!empty($comments)): ?>
                <?php foreach ($comments as $key => $comment): ?>
                    <p class="<?php echo (($key % 2) > 0) ? 'bg-light' : ''; ?>"><?php echo $comment; ?></p>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="text-muted">Скажите, что вы думаете об этом фото?</p>
            <?php endif; ?>

            <form method="post">
                <div class="form-group">
                    <label for="comment">Ваш комментарий</label>
                    <textarea class="form-control" id="comment" name="comment" rows="3" required></textarea>
                </div>
                <hr>
                <button type="submit" class="btn btn-primary">Отправить</button>
            </form>
        </div>
        </div>
    </div>



    <!-- Options: jQuery, Popper.js, and Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
        integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI"
        crossorigin="anonymous"></script>
</body>
</html>