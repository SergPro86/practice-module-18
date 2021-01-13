<?php 
require 'config.php';

$errors = [];
$messages = [];

if (!empty($_FILES)) {
    for ($i=0; $i < count($_FILES['files']['name']); $i++) { 

        $fileName = $_FILES['files']['name'][$i];

        if ($_FILES['files']['size'][$i] > UPLOAD_MAX_SIZE) {
            $errors[] = 'Недопустимый размер файла ' . $fileName;
            continue;
        }

        if (!in_array($_FILES['files']['type'][$i], ALLOWED_TYPES)) {
            $errors[] = 'Недопустимый формат файла ' . $fileName;
            continue;
        }

        $filePath = UPLOAD_DIR . '/' . basename($fileName);

        if (!move_uploaded_file($_FILES['files']['tmp_name'][$i], $filePath)) {
            $errors[] = 'Ошибка загрузки файла ' . $fileName;
            continue;
        }

    }

    if (empty($errors)) {
        $messages[] = 'Файлы были загружены';
    }

}

if (!empty($_POST['name'])) {
    
    $filePath = UPLOAD_DIR . '/' . $_POST['name'];
    $commentPath = COMMENT_DIR . '/' . $_POST['name'] . ' .txt';

    unlink($filePath);

    if (file_exists($commentPath)) {
        unlink($commentPath);
    }

    $messages[] = 'Файл был удален';
}

$files = scandir(UPLOAD_DIR);
$files = array_filter($files, function ($file) {
    return !in_array($file, ['.', '..', '.gitkeep']);
});

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css"
        integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <title>Галерея изображений | Список файлов</title>
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

        <h2>Список файлов</h2>

        <div class="mb-4">
            <?php if(!empty($files)) : ?>
                <div class="row">
                    <?php foreach($files as $file) : ?>

                        <div class="col-12 col-sm-3 mb-4">
                            <form method="post">
                                <input type="hidden" name="name" value="<?php echo $file; ?>">
                                <button type="submit" class="close" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </form>
                            <a href="<?php echo URL . '/file.php?name=' . $file; ?>" title="Просмотр полного изображения">
                            <img src="<?php echo URL . '/' . UPLOAD_DIR . '/' . $file ?>" class="img-thumbnail"
                                    alt="<?php echo $file; ?>">
                            </a>
                        </div>

                    <?php endforeach; ?>
                </div>
                <?php else: ?>
                    <div class="alert alert-secondary">Нет загруженных изображений</div>
                <?php endif; ?>
        </div>

        <form method="post" enctype="multipart/form-data">
        <div class="custom-file">
            <input type="file" class="custom-file-input" name="files[]" id="customFile" multiple required>
            <label class="custom-file-label" for="customFile" data-browse="Выбрать">Выберите файлы</label>
            <small class="form-text text-muted">
                Максимальный размер файла: <?php echo UPLOAD_MAX_SIZE / 1000000; ?>Мб.
                Допустимые форматы: <?php echo implode(', ', ALLOWED_TYPES) ?>.
            </small>
        </div>
        <hr>
        <button type="submit" class="btn btn-primary">Загрузить</button>
        </form>

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