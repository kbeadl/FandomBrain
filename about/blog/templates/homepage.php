<?php include "templates/include/header.php" ?>

<?php foreach ( $results['articles'] as $article ) { ?>
            <div id="rss">
              <a href="http://fandombrain.com/about/blog/" id="rssa">FandomBrain Blog</a><p><br></p>
             <?php echo htmlspecialchars( $article->title )?><p><br></p>
            <span class="pubDate"><?php echo date('j F', $article->publicationDate)?></span><p><br></p>
          <p class="summary">
            <?php if ( $imagePath = $article->getImagePath( IMG_TYPE_THUMB ) ) { ?>
              <a href=".?action=viewArticle&amp;articleId=<?php echo $article->id?>"><img class="articleImageThumb" src="<?php echo $imagePath?>" alt="Article Thumbnail" /></a><p><br></p>
            <?php } ?>
          <?php echo htmlspecialchars( $article->summary )?><p><br></p>
        </p>
        <p><br></p>
        <div id="readm"><a id="rssa">Read more</a></div>
        </div>

<?php } ?>

<?php include "templates/include/footer.php" ?>

