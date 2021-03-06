<?php
error_reporting(0);
if(isset($_POST['checkBoxArray'])){
    foreach ($_POST['checkBoxArray'] as $postValueId){
    $bulk_options= $_POST['bulk_options'];
    switch ($bulk_options){
        case 'Published':
            $query= "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id= '$postValueId' ";
            $update_to_published_status= mysqli_query($connection,$query);
            confirmQuery($update_to_published_status);
            break;
        case 'Draft':
            $query= "UPDATE posts SET post_status = '{$bulk_options}' WHERE post_id= '$postValueId' ";
            $update_to_draft_status= mysqli_query($connection,$query);
            confirmQuery($update_to_draft_status);
            break;
        case 'Delete':
            $query= "DELETE FROM posts WHERE post_id= '$postValueId' ";
            $update_to_delete_status= mysqli_query($connection,$query);
            confirmQuery($update_to_delete_status);
            break;
        case 'Clone':
            $query= "SELECT * FROM posts WHERE post_id= '{$postValueId}' ";
            $select_post_query= mysqli_query($connection,$query);

            while($row = mysqli_fetch_array($select_post_query)) {
                $post_user = escape($row['post_user']);
                $post_title = escape($row['post_title']);
                $post_category_id = escape($row['post_category_id']);
                $post_status = escape($row['post_status']);
                $post_image = escape($row['post_image']);
                $post_tags = escape($row['post_tags']);
                $post_date = escape($row['post_date']);
                $post_content=escape($row['post_content']);
            }
            $query= "INSERT INTO posts(post_category_id, post_title, post_user, post_date, post_image, post_content,post_tags, post_status) ";
            $query .= "VALUES({$post_category_id},'{$post_title}','{$post_user}',now(),'{$post_image}','{$post_content}','{$post_tags}','{$post_status}' ) ";
            $copy_query= mysqli_query($connection,$query);
            if(!$copy_query){
                die("QUERY FAILED" . mysqli_error($connection));
            }
            break;
    }
    }

}
?>

<form action="" method="post">
<table class="table table-bordered table-hover">

    <div id="bulkOptionsContainer" class="col-xs-4">
        <select class="form-control" name="bulk_options" id="">
            <option value="">Select Options</option>
            <option value="Published">Publish</option>
            <option value="Draft">Draft</option>
            <option value="Delete">Delete</option>
            <option value="Clone">Clone</option>
        </select>
    </div>
    <div class="col-xs-4">
        <input type="submit" name="submit" class="btn btn-success" value="Apply">
        <a class="btn btn-primary" href="posts.php?source=add_post">Add New</a>
    </div>

                        <thead>
                        <tr>
                            <th><input id="selectAllBoxes" type="checkbox"></th>
                            <th>Post id</th>
                            <th>User</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Image</th>
                            <th>Tags</th>
                            <th>Comments</th>
                            <th>Date</th>
                            <th>View Post</th>
                            <th>Edit</th>
                            <th>Delete</th>
                        </tr>
                        </thead>
                    <tbody>

                        <?php
                        $query= "SELECT * FROM posts ORDER BY post_id DESC ";
    $select_posts= mysqli_query($connection,$query);
    while($row = mysqli_fetch_assoc($select_posts)) {
        $post_id = escape($row['post_id']);
        $post_user = escape($row['post_user']);
        $post_user = escape($row['post_user']);
        $post_title =escape($row['post_title']);
        $post_category_id =escape($row['post_category_id']);
        $post_status =escape($row['post_status']);
        $post_image =escape($row['post_image']);
        $post_tags =escape($row['post_tags']);
        $post_comment_count =escape($row['post_comment_count']);
        $post_date =escape($row['post_date']);

        echo "<tr>";
        ?>
        <td><input class='checkBoxes'  type='checkbox' name='checkBoxArray[]' value='<?php echo $post_id; ?>'> </td>
        <?php
        echo "<td>$post_id </td>";

        if(!empty($post_author)) {
            echo "<td>$post_author </td>";
        }
        elseif(!empty($post_user)) {
            echo "<td>$post_user</td>";
        }


        echo "<td>$post_title </td>";

                        $query= "SELECT * FROM categories WHERE cat_id = {$post_category_id} ";
                        $select_categories_id= mysqli_query($connection,$query);
                        while($row = mysqli_fetch_assoc($select_categories_id)) {
                            $cat_id = escape($row['cat_id']);
                            $cat_title = escape($row['cat_title']);


                            echo "<td>{$cat_title} </td>";
                        }




        echo "<td>$post_status </td>";
        echo "<td><img width='100' src='../images/{$post_image}' alt='image'></td>";
        echo "<td>$post_tags </td>";

        $query="SELECT * FROM comments WHERE comment_post_id=$post_id";
        $send_comment_query=mysqli_query($connection,$query);
        $row=mysqli_fetch_array($send_comment_query);
        $comment_id=escape($row['comment_id']);
        $count_comments= mysqli_num_rows($send_comment_query);

        echo "<td><a href='post_comments.php?id=$post_id'>$count_comments</a></td>";

        echo "<td>$post_date </td>";
        echo "<td><a href='../post.php?p_id={$post_id}'>View Post </a> </td>";
        echo "<td><a href='posts.php?source=edit_post&p_id={$post_id}'>Edit </a> </td>";
        echo "<td><a href='posts.php?delete={$post_id}'>Delete </a> </td>";
        echo "</tr>";

    }
                        ?>
                    </tbody>
                    </table>
</form>

 <?php

 if(isset($_GET['delete'])){ //DELETE POST
    $the_post_id= escape($_GET['delete']);
    $query = "DELETE FROM posts WHERE post_id={$the_post_id}";
    $delete_query= mysqli_query($connection,$query);
    confirmQuery($delete_query);
    header("Location: posts.php");
 }


 ?>
