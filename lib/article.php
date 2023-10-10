
<?php

function getArticleById(PDO $pdo, int $id):array|bool
{
    $query = $pdo->prepare("SELECT * FROM articles WHERE id = :id");
    $query->bindValue(":id", $id, PDO::PARAM_INT);
    $query->execute();
    return $query->fetch(PDO::FETCH_ASSOC);
}

function getArticles(PDO $pdo, int $limit = 20, int $page = null, $category_id = null):array|bool
{

    /*
        @todo_done faire la requête de récupération des articles
        La requête sera différente selon les paramètres passés, commencer par le BASE de base
    */
    if (!empty($page)){
        $start_row = ($page-1) * $limit;
    }

    $query = "SELECT * FROM articles"
            . (!empty($category_id) ? " WHERE category_id = {$category_id}" : "")
            . " ORDER BY id DESC"
            . (!empty($limit) ? " LIMIT {$limit}" : "")
            . (isset($start_row) ? " OFFSET {$start_row}" : "");
    $sql = $pdo->prepare($query);
    $sql->execute();
    $result = $sql->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}

function getTotalArticles(PDO $pdo, int|null $category_id = null):int|bool
{
    /*
        @todo_done récupérer le nombre total d'article (avec COUNT)
    */
    $query = "SELECT COUNT(*) total from articles" 
            . (!empty($category_id) ? " WHERE category_id = {$category_id}" : "");
    $sql = $pdo->prepare($query);
    $sql->execute();
    $result = $sql->fetch(PDO::FETCH_ASSOC);
    return $result['total'];
}

function saveArticle(PDO $pdo, string $title, string $content, string|null $image, int $category_id, int $id = null):bool 
{
    $params = [
        "category_id" => $category_id,
        "title" => $title,
        "content" => $content,
        "image" => $image
    ];

    if ($id === null) {
        /*
            @todo_done si id est null, alors on fait une requête d'insection
        */
        $query = "INSERT INTO articles (category_id, title, content, image) 
                VALUES (:category_id, :title, :content, :image)";
    } else {
        /*
            @todo_done sinon, on fait un update
        */
        
        $query = "UPDATE articles
                SET category_id = :category_id, title = :title, content = :content, image = :image 
                WHERE id = :id";
        $params["id"] = $id;
    }

    // @todo_done on bind toutes les valeurs communes
    
    $sql = $pdo->prepare($query);
    return $sql->execute($params);
}

function deleteArticle(PDO $pdo, int $id):bool
{
    
    /*
        @todo_done Faire la requête de suppression
    */
    $query = $pdo->prepare("DELETE FROM articles WHERE id = :id");
    $query->bindValue(":id", $id, PDO::PARAM_INT);
    $query->execute();

    if ($query->rowCount() > 0) {
        return true;
    } else {
        return false;
    }
    
}

function addComment(PDO $pdo, int $id_article, array $user, string $message):bool {
    $params = [
        "id_article" => $id_article,
        "id_user" => $user['id'],
        "content" => $message,
    ];

        $query = "INSERT INTO commentaires (id_article, id_user, contenu) 
                VALUES (:id_article, :id_user, :content)";

    
    $sql = $pdo->prepare($query);
    return $sql->execute($params);
}