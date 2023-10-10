<?php
require_once __DIR__ . "/lib/config.php";
require_once __DIR__ . "/lib/session.php";
require_once __DIR__ . "/lib/pdo.php";
require_once __DIR__ . "/lib/article.php";
require_once __DIR__ . "/lib/category.php";
require_once __DIR__ . "/templates/header.php";

// @todo_done On doit appeler getArticale pour récupérer les articles et faire une boucle pour les afficher

if (isset($_GET['category_id'])) {
    $category_filter = $_GET['category_id'] != 'all' ? $_GET['category_id'] : null;
} else {
    $category_filter = null;
}

if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
} else {
    $page = 1;
}

$articles = getArticles($pdo, _ACTU_ITEM_LIMIT_, $page, $category_filter);
  
$totalArticles = getTotalArticles($pdo, $category_filter);
  
$totalPages = ceil($totalArticles / _ACTU_ITEM_LIMIT_);

$categories = getCategories($pdo);
?>

<h1>TechTrendz Actualités</h1>


<div style="text-align: right;margin: 10px">
    <?php if ($page > 1) { ?>
        <a class="btn btn-warning" href="?page=<?= $page-1 . (!empty($category_filter) ? "&category_id={$category_filter}" : "") ?>">Afficher les articles plus récents</a>
    <?php } ?>
    <form method="GET">
        <select name="category_id" id="category" onchange="this.form.submit()" class="form-select" style="width:300px; margin-left:auto; margin-top:10px">
            <option value="0">Toutes les catégories</option>
            <?php foreach ($categories as $category) { ?>
                <option value="<?= $category['id'] ?>" <?php if ($category_filter == $category['id']) { ?>selected="selected" <?php }; ?>><?= $category['name'] ?></option>
            <?php } ?>
        </select>
    </form>
</div>


<div class="row text-center">

    <?php foreach ($articles as $article): ?>
        <div class="col-md-4 my-2 d-flex">
            <div class="card">
                <?php if(!empty($article["image"])): ?>
                    <img src="/uploads/articles/<?= $article["image"] ?>" class="card-img-top" alt="<?= $article["title"] ?>">
                <?php else: ?>
                    <img src="/assets/images/default-article.jpg" class="card-img-top" alt="<?= $article["title"] ?>">
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title"><?= $article["title"] ?></h5>
                    <a href="actualite.php?id=<?= $article["id"] ?>" class="btn btn-primary">Lire la suite</a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>

</div>

<?php if ($page < $totalPages) { ?>
    <div style="text-align: right;margin: 10px;">
        <a class="btn btn-warning" href="?page=<?= $page+1 . (!empty($category_filter) ? "&category_id={$category_filter}" : "") ?>">Afficher les articles plus anciens</a>
    </div>
<?php } ?>

<?php require_once __DIR__ . "/templates/footer.php"; ?>