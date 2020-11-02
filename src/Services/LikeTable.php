<?php


namespace App\Services;


use App\Entity\Post;
use App\Entity\Profile;
use Symfony\Component\Security\Core\User\UserInterface;

class LikeTable
{
    public function postIndexForeignKey(Post $post){
        $id = $post->getId();
        $idx_post = 'IDX_LIKE_POST_'.$id;
        $idx_comment_post = 'IDX_LIKE_COMMENT_POST_'.$id;
        $idx_response_post = 'IDX_LIKE_RESPONSE_COMMENT_POST_'.$id;
        $idx_profile = 'IDX_LIKE_PROFILE_'.$id;
        $idx_comment_profile = 'IDX_LIKE_COMMENT_PROFILE_'.$id;
        $idx_response_profile = 'IDX_LIKE_RESPONSE_COMMENT_PROFILE_'.$id;
        $idx_user = 'IDX_USER_POST_'.$id;
        $fk_post = 'FK_LIKE_POST_'.$id;
        $fk_comment_post = 'FK_LIKE_COMMENT_POST_'.$id;
        $fk_response_post = 'FK_LIKE_RESPONSE_COMMENT_POST_'.$id;
        $fk_profile = 'FK_LIKE_PROFILE_'.$id;
        $fk_comment_profile = 'FK_LIKE_COMMENT_PROFILE_'.$id;
        $fk_response_profile = 'FK_LIKE_RESPONSE_COMMENT_PROFILE_'.$id;
        $fk_user = 'FK_USER_POST_'.$id;


        return array('idx_post' => $idx_post, 'idx_comment_post' => $idx_comment_post, 'idx_response_post' =>$idx_response_post, 'idx_profile' => $idx_profile,
                     'idx_comment_profile' => $idx_comment_profile, 'idx_response_profile' => $idx_response_profile, 'fk_post' => $fk_post,
                     'fk_comment_post' => $fk_comment_post, 'fk_response_post' => $fk_response_post, 'fk_profile' => $fk_profile, 'fk_comment_profile' => $fk_comment_profile,
                     'fk_response_profile' => $fk_response_profile, 'idx_user' => $idx_user, 'fk_user' => $fk_user);
    }

    public function profileIndexForeignKey(Profile $profile){
        $id = $profile->getId();
        $idx_post = 'IDX_PRO_LIKE_POST_'.$id;
        $idx_comment_post = 'IDX_PRO_LIKE_COMMENT_POST_'.$id;
        $idx_response_post = 'IDX_PRO_LIKE_RESPONSE_COMMENT_POST_'.$id;
        $idx_profile = 'IDX_PRO_LIKE_PROFILE_'.$id;
        $idx_comment_profile = 'IDX_PRO_LIKE_COMMENT_PROFILE_'.$id;
        $idx_response_profile = 'IDX_PRO_LIKE_RESPONSE_COMMENT_PROFILE_'.$id;
        $idx_user = 'IDX_PRO_USER_POST_'.$id;
        $fk_post = 'FK_PRO_LIKE_POST_'.$id;
        $fk_comment_post = 'FK_PRO_LIKE_COMMENT_POST_'.$id;
        $fk_response_post = 'FK_PRO_LIKE_RESPONSE_COMMENT_POST_'.$id;
        $fk_profile = 'FK_PRO_LIKE_PROFILE_'.$id;
        $fk_comment_profile = 'FK_PRO_LIKE_COMMENT_PROFILE_'.$id;
        $fk_response_profile = 'FK_PRO_LIKE_RESPONSE_COMMENT_PROFILE_'.$id;
        $fk_user = 'FK_PRO_USER_POST_'.$id;


        return array('idx_post' => $idx_post, 'idx_comment_post' => $idx_comment_post, 'idx_response_post' =>$idx_response_post, 'idx_profile' => $idx_profile,
                     'idx_comment_profile' => $idx_comment_profile, 'idx_response_profile' => $idx_response_profile, 'fk_post' => $fk_post,
                     'fk_comment_post' => $fk_comment_post, 'fk_response_post' => $fk_response_post, 'fk_profile' => $fk_profile, 'fk_comment_profile' => $fk_comment_profile,
                     'fk_response_profile' => $fk_response_profile, 'idx_user' => $idx_user, 'fk_user' => $fk_user);
    }
}
