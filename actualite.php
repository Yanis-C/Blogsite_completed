<?php
require_once __DIR__ . "/lib/config.php";
require_once __DIR__ . "/lib/session.php";
require_once __DIR__ . "/lib/pdo.php";
require_once __DIR__ . "/lib/article.php";
require_once __DIR__ . "/templates/header.php";


//@todo_done On doit récupérer l'id en paramètre d'url et appeler la fonction getArticleById récupérer l'article
if ($_GET["id"]) {
    $article = getArticleById($pdo, $_GET['id']);
}

$messages = [];
$errors = [];

if (isset($_POST["addComment"])) {
    if (!empty($_POST['message'])) {
        if (addComment($pdo, $_GET["id"], $_SESSION["user"], $_POST['message'])) {
            $messages[] = "Commentaire envoyé avec succès";
        } else {
            $errors[] = "Erreur lors de l'envoi du commentaire";
        }
    } else {
        $errors[] = "Le commentaire est vide";
    }
}
?>

<?php foreach($messages as $message) { ?>
    <div class="alert alert-success">
        <?=$message; ?>
    </div>
<?php } ?>

<?php foreach($errors as $error) { ?>
    <div class="alert alert-success">
        <?=$error; ?>
    </div>
<?php } ?>

<?php if ($article): ?>
    <div class="row flex-lg-row-reverse align-items-center g-5 py-5">
        <div class="col-10 col-sm-8 col-lg-6">
            <?php if(!empty($article["image"])): ?>
                <img src="/uploads/articles/<?= $article["image"] ?>" class="d-block mx-lg-auto img-fluid" alt="Bootstrap Themes" width="700" height="500" loading="lazy">
            <?php endif; ?>
        </div>
        <div class="col-lg-6">
            <h1 class="display-5 fw-bold text-body-emphasis lh-1 mb-3"><?= $article["title"] ?></h1>
            <p class="lead"><?= $article["content"] ?></p>
        </div>
    </div>


    <?php if (isset($_SESSION['user'])) { ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="message" class="form-label">Ajouter un commentaire:</label>
                <textarea class="form-control" id="message" name="message" rows="3"></textarea>
            </div>

            <input type="submit" name="addComment" class="btn btn-primary" value="Envoyer">

        </form>    
        
        
    <?php } ?>
<?php else : ?>
    <p>Article non trouvé.</p>
<?php endif; ?>



<?php require_once __DIR__ . "/templates/footer.php"; ?>